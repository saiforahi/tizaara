<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource->toArray();

        unset($resource['last_message']);
        unset($resource['participants']);

        $messageable = $this->participants->first(function ($e) {
            return $e->messageable_id == auth()->user()->id;
        });

        return array_merge($resource, [
            'conversation_id' => $this->id,
            'messageable_id' => $messageable->messageable_id ?? '',
            'messageable_type' => $messageable->messageable_type ?? '',
            'settings' => $messageable->settings ?? '',
            'conversation' => array_merge($resource, [
                'last_message' => $this->last_message,
                'participants' => $this->participants,
            ]),
        ]);
    }
}
