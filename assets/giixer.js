yii.giixer = (function ($) {

    var initLinkVisibilityToggler = function () {
        $('.visibility-toggler').on('click', function (e, r) {
            e.preventDefault();
            var href = $(e.target).attr('href'),
                    elm = $(href);

            if (elm) {
                elm.toggleClass('hide');
            }
        });
    }

    var initOnChangeVisibilityToggle = function (toggler, targets, condition) {

        if ($.isArray(targets)) {
            toggler.change(function () {
                toggleVisibility(toggler, targets, condition);
            });
        }
    }

    var toggleVisibility = function (toggler, targets, condition) {
        $.each(targets, function (i, e) {
            $(e).toggle(toggler.is(condition));
        })
    }

    return {
        init: function () {

            // inits link (a[href="#taget"]) visibility togglers
            initLinkVisibilityToggler();


            // register on check visibility toggler callback
            var generateMutationToggler = $('form #generator-generatemutation');
            var generateMutationTogglerTargets = [
                'form .field-generator-mutationjointablename',
                'form .field-generator-mutationsourcetablename',
                'form .field-generator-generatesluggablemutation',
                'form .field-generator-sluggablemutationattribute',
                'form .field-generator-sluggablemutationensureunique',
                'form .field-generator-sluggablemutationimutable',
            ];
            initOnChangeVisibilityToggle(generateMutationToggler, generateMutationTogglerTargets, ':checked');

            // register on check visibility toggler callback
            var generateTimestampBehaviorToggler = $('form #generator-generatetimestampbehavior');
            var generateTimestampBehaviorTogglerTargets = [
                'form .field-generator-timestampcreatedatattribute',
                'form .field-generator-timestampupdatedatattribute',
            ];
            initOnChangeVisibilityToggle(generateTimestampBehaviorToggler, generateTimestampBehaviorTogglerTargets, ':checked');

            // toggle visibility once at begin to ensure elements are in proper state
            toggleVisibility(generateMutationToggler, generateMutationTogglerTargets, ':checked');
            toggleVisibility(generateTimestampBehaviorToggler, generateTimestampBehaviorTogglerTargets, ':checked');
        }
    };
})(jQuery);
