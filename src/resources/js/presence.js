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
            setInterval($.proxy(this, 'isAlive'),6000);
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
                        console.log('OK');
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