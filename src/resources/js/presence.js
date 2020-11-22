/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

(function($)
{
    /**
     * EnupalPresence class
     */
    var EnupalPresence = Garnish.Base.extend({

        userId: null,
        elementId:null,
        hashMap: {},

        /**
         * The constructor.
         */
        init: function(userId, elementId)
        {
            this.userId = userId;
            this.elementId = elementId;
            setInterval($.proxy(this, 'isAlive'), 5000);
            $("#details").append('<div id="enupal-presence" class="meta read-only hidden"></div>');
        },

        isAlive: function(event)
        {
            var that = this;
            var data = {
                'userId' : this.userId,
                'elementId' : this.elementId
            };

            Craft.postActionRequest('enupal-presence/presence/is-alive', data, $.proxy(function(response, textStatus) {
                if (textStatus === 'success') {
                    if (response.success)
                    {
                        if (response.userPhotos) {
                            $("#enupal-presence").removeClass('hidden');
                            var currentUserIds = {};
                            for( var userId in response.userPhotos ) {
                                if (that.hashMap.hasOwnProperty(userId)) {
                                    console.log('we already have this one');
                                    continue;
                                }
                                $("#enupal-presence").html(response.userPhotos[userId]);
                                that.hashMap[userId] = true;
                                currentUserIds[userId] = true;
                            }
                            //after finish loop, check difference that.hashMap and currentUsersIds and add fade effect to remove div
                            /*
                            for( var userId in response.userPhotos ) {
                                console.log(userId);
                                $("#enupal-presence").html(response.userPhotos[userId]);
                            }*/
                        }

                    }
                }
                else {
                    Craft.cp.displayError(Craft.t('app', 'An unknown error occurred.'));
                }
            }, this));
        }
    });

    window.EnupalPresence = EnupalPresence;

})(jQuery);