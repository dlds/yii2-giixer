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
                'form .field-generator-mutationignoredformattributes',
            ];
            initOnChangeVisibilityToggle(generateMutationToggler, generateMutationTogglerTargets, ':checked');

            // register on check visibility toggler callback
            var generateSluggableToggler = $('form #generator-generatesluggablebehavior');
            var generateSluggableTogglerTargets = [
                'form .field-generator-sluggablebehaviorsourceattribute',
                'form .field-generator-sluggablebehaviortargetattribute',
                'form .field-generator-sluggablebehaviorensureunique',
                'form .field-generator-sluggablebehaviorimutable',
            ];
            initOnChangeVisibilityToggle(generateSluggableToggler, generateSluggableTogglerTargets, ':checked');


            // register on check visibility toggler callback
            var generateTimestampBehaviorToggler = $('form #generator-generatetimestampbehavior');
            var generateTimestampBehaviorTogglerTargets = [
                'form .field-generator-timestampcreatedatattribute',
                'form .field-generator-timestampupdatedatattribute',
            ];
            initOnChangeVisibilityToggle(generateTimestampBehaviorToggler, generateTimestampBehaviorTogglerTargets, ':checked');
            
            // register on check visibility toggler callback
            var generateSortableBehaviorToggler = $('form #generator-generatesortablebehavior');
            var generateSortableBehaviorTogglerTargets = [
                'form .field-generator-sortablecolumnattribute',
                'form .field-generator-sortableindexattribute',
                'form .field-generator-sortablekeyattribute',
                'form .field-generator-sortablerestrictionsattribute',
            ];
            initOnChangeVisibilityToggle(generateSortableBehaviorToggler, generateSortableBehaviorTogglerTargets, ':checked');
            
            // register on check visibility toggler callback
            var generateGalleryBehaviorToggler = $('form #generator-generategallerybehavior');
            var generateGalleryBehaviorTogglerTargets = [
                'form .field-generator-gallerytablename',
            ];
            initOnChangeVisibilityToggle(generateGalleryBehaviorToggler, generateGalleryBehaviorTogglerTargets, ':checked');
            
            // register on check visibility toggler callback
            var generateAlwaysAssignableBehaviorToggler = $('form #generator-generatealwaysassignablebehavior');
            var generateAlwaysAssignableBehaviorTogglerTargets = [
                'form .field-generator-alwaysassignabletablename',
            ];
            initOnChangeVisibilityToggle(generateAlwaysAssignableBehaviorToggler, generateAlwaysAssignableBehaviorTogglerTargets, ':checked');

            // toggle visibility once at begin to ensure elements are in proper state
            toggleVisibility(generateMutationToggler, generateMutationTogglerTargets, ':checked');
            toggleVisibility(generateSluggableToggler, generateSluggableTogglerTargets, ':checked');
            toggleVisibility(generateTimestampBehaviorToggler, generateTimestampBehaviorTogglerTargets, ':checked');
            toggleVisibility(generateSortableBehaviorToggler, generateSortableBehaviorTogglerTargets, ':checked');
            toggleVisibility(generateGalleryBehaviorToggler, generateGalleryBehaviorTogglerTargets, ':checked');
            toggleVisibility(generateAlwaysAssignableBehaviorToggler, generateAlwaysAssignableBehaviorTogglerTargets, ':checked');
        }
    };
})(jQuery);
