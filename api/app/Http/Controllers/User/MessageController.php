<?php

namespace App\Http\Controllers\User;

use App\ChatConversationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Product;
use App\Traits\FileUpload;
use App\User;
use Chat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Musonza\Chat\Models\Conversation;

class MessageController extends Controller
{
    use FileUpload;

    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function contactSupplier($id, Request $request)
    {
        $product = Product::query()->find($id);
        if (!$product) {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }

        try {
            $from = auth()->user();
            $to = $product->user;
            $participants = [$from, $to];
            $conversation = Chat::conversations()->between($from, $to);
            if (empty($conversation)) {
                $conversation = Chat::createConversation($participants)->makeDirect();
            }

            /*check has any previous conversation as a buyer*/
            $count = auth()->user()->messageable1->where(['messageable2_id'=>$to->id,'message_type'=>'buyer'])->count();

            if ($count <1){

                /*
             * chat conversation type data prepare
             * */
                $data=[
                    ['messageable1_type'=>'App\User',
                        'messageable1_id'=>auth()->id(),
                        'messageable2_type'=>'App\User',
                        'messageable2_id'=>$to->id,
                        'message_type'=>'buyer',
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ],
                    [
                        'messageable1_type'=>'App\User',
                        'messageable1_id'=>$to->id,
                        'messageable2_type'=>'App\User',
                        'messageable2_id'=>auth()->id(),
                        'message_type'=>'supplier',
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ]
                ];
                /*conversation type store*/
                ChatConversationType::insert($data);
            }

            /*
             * product information store
             * */
            $message = Chat::message(json_encode(
                    $product->with(['brand'=>function($q){$q->select('id','name');}])->where('id',$product->id)->first(['id','name','slug','brand_id','colors','thumbnail_img'])
                ))
                    ->type('product')
                    ->from($from)
                    ->to($conversation)
                    ->send();
            $message->message_type='buyer';
            $message->save();
            /*
             * normal text message store
             * */
            $message = Chat::message($request->text)
                ->from($from)
                ->to($conversation)
                ->send();
            $message->message_type='buyer';
            $message->save();
            /*
             * if file sent then store
             * */
            if (isset($request->file)) {
                $message = Chat::message($request->file)
                    ->type('image')
                    ->message_type('buyer')
                    ->from($from)
                    ->to($conversation)
                    ->send();
                $message->message_type='buyer';
                $message->save();
            }
            $conversation->update(['data' => [
                'id' => $request->product_id,
                'qty' => $request->product_qty,
                'unit' => $request->product_unit,
            ]]);

            return response()->json(['result' => 'Ok'], 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            return response()->json(['result' => 'Error'], 500);
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $auth = auth()->user();

        $conversations = Conversation::query()
            ->with([
                'last_message',
                'participants',
                'participants.messageable'
            ])
            ->whereHas('participants', function (Builder $query) use ($auth, $keyword) {
                $query->where('messageable_id', $auth->id);
            });

        if (isset($keyword)) {
            $conversations
                ->whereHas('participants', function (Builder $query) use ($auth, $keyword) {
                    $query->whereHasMorph('messageable', User::class, function (Builder $query) use ($auth, $keyword) {
                        $query->where('first_name', 'LIKE', "%$keyword%")
                            ->orWhere('last_name', 'LIKE', "%$keyword%");
                    });
                });
        }

        return $this->pagination($conversations->paginate(10), ConversationResource::class);
    }

    public function conversations(Request $request)
    {
        $conversations = Chat::conversations()
            ->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant(auth()->user())
            ->limit(10)
            ->page($request->page)
            ->get();

        return ['data' => $conversations];
    }

    public function messages($conversationId, Request $request)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $messages = Chat::conversation($conversation)
            ->setPaginationParams(['sorting' => 'asc'])
            ->setParticipant(auth()->user())
            ->limit(30)
            ->page($request->page)
            ->getMessages();

        return ['data' => $messages];
    }

    public function sendMessage($conversationId, Request $request)
    {
        $type = $request->get('type', 'text');
        $conversation = Chat::conversations()->getById($conversationId);
        $message = Chat::message($request->message)
            ->type($type)
            ->from(auth()->user())
            ->to($conversation)
            ->send();
        $message->message_type=$request->message_type;
        $message->save();

        return $message;
    }

    public function uploadAttachment(Request $request)
    {
        $file = $this->saveImagesBin($request, 'file', 'upload/message/', 200, 300);

        return [
            'url' => url($file)
        ];
    }

    /*
     * auth user unseen message count
     * */
    public function unseenMessageCount()
    {
        $unreadCount = Chat::messages()->setParticipant(auth()->user())->unreadCount();
        return response()->json($unreadCount,200);
    }

    /*
     * method for get  auth user conversation types
     * */
    public function chatConversationTypes()
    {
        $conversations = auth()->user()->messageable1()->with([
            'companyBasic'=>function($q){$q->select('id','user_id','address_type','display_name','name','email','phone','office_space'); },
            'companyDetails'=>function($q){$q->select('id','user_id','company_logo'); }
        ])->get();
        return response()->json($conversations,200);
    }
}
