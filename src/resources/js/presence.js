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
        firstLoad: true,

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

        hideElements: function(elements)
        {
            for(var userId in elements) {
                // Hide they are no loger viewing
                this.hideUser(userId);
            }
        },

        hideUser(userId)
        {
            $("#presence-user-"+userId).fadeOut("normal", function() {
                $(this).addClass('hidden');
            });
        },

        displayUser(userId)
        {
            $("#presence-user-"+userId).fadeIn("normal", function() {
                $(this).removeClass('hidden').css("display", "inline-block");
            });
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
                            for (var userId in response.userPhotos) {
                                if ($("#enupal-presence").hasClass('hidden')) {
                                    $("#enupal-presence").removeClass('hidden').css("display", "");
                                }
                                currentUserIds[userId] = true;
                                if (that.hashMap.hasOwnProperty(userId)) {
                                    that.displayUser(userId);
                                    continue;
                                }
                                $("#enupal-presence").append(response.userPhotos[userId]).fadeIn('slow');
                                that.hashMap[userId] = true;
                            }
                            // ignore the first time
                            if (that.firstLoad) {
                                that.firstLoad = false;
                                return;
                            }
                        }

                        var copy = $.extend(true,{},that.hashMap);
                        if (currentUserIds) {
                            for(var userId in currentUserIds) {
                                delete copy[userId];
                            }
                        }

                        if (copy) {
                            that.hideElements(copy);
                        }

                        if ($.isEmptyObject(currentUserIds)) {
                            $("#enupal-presence").fadeOut("normal", function() {
                                $(this).addClass('hidden');
                            });
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