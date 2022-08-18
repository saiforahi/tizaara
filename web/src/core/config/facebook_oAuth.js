import {facebook_app_id} from "@/core/config/app";

export const initFacebookSdk = () => {
    return new Promise(() => {
        window.fbAsyncInit = function () {
            FB.init({
                appId: facebook_app_id,
                cookie: true,
                xfbml: true,
                version: 'v12.0'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    });
}