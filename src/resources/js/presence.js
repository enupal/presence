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
                            if (response.userPhotos.length) {
                                $("#enupal-presence").removeClass('hidden');
                                $("#enupal-presence").html(response.userPhotos);
                            }else {
                                $("#enupal-presence").addClass('hidden');
                            }

                        } else {
                            $("#enupal-presence").addClass('hidden');
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