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
                        var currentUserIds = {};

                        if (response.userPhotos) {
                            $("#enupal-presence").removeClass('hidden');
                            for (var userId in response.userPhotos) {
                                if (that.hashMap.hasOwnProperty(userId)) {
                                    $("#presence-user" + userId).removeClass('hidden');
                                    console.log('we already have this one');
                                    continue;
                                }
                                $("#enupal-presence").hide().append(response.userPhotos[userId]).fadeIn('slow');
                                that.hashMap[userId] = true;
                                currentUserIds[userId] = true;
                            }
                        }

                        var copy = $.extend(true,{},that.hashMap);
                        console.log(copy);

                        if (currentUserIds) {
                            for(var userId in currentUserIds) {
                                delete copy[userId];
                            }
                        }

                        if (copy) {
                            for(var userId in copy) {
                                console.log(userId);
                                // Hide they are no loger viewing
                                $("#presence-user"+userId).fadeOut("normal", function() {
                                    console.log('removing element');
                                    $(this).addClass('hidden');
                                });
                            }
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