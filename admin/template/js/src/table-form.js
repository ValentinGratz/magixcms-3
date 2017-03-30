var tableForm = (function ($, undefined) {
    /**
     * Initializes the multi-select checkboxes
     */
    function initCheckboxSelect() {
        $('.check-all').on('change',function(){
            var table = $(this).data('table'),
                chb = $('#'+table+' input[type="checkbox"]:enabled');
            if($(this).prop('checked')) {
                chb.prop('checked',true);
            } else {
                chb.prop('checked',false);
            }
        });

        $('.update-checkbox').on('click',function(e){
            e.preventDefault();
            var table = $(this).data('table'),
                chb = $('#'+table+' input[type="checkbox"]:enabled');

            if($(this).val() == 'check-all') {
                $('#'+table+' .check-all').prop('checked',true);
                chb.prop('checked',true);
            } else {
                $('#'+table+' .check-all').prop('checked',false);
                chb.prop('checked',false);
            }
        });
    }

    /**
     * Initialises the validation of the delete forms
     * and send delete request to the indicated controller
     * @param modal
     * @param id
     * @param controller
     * @param subcontroller
     */
    function delete_data(modal, id, controller, subcontroller) {
        $(modal+' input[type="hidden"]').val(id);
        $(modal).modal('show');
        var url = '/'+controller+'.php?action=delete';
        if(subcontroller)
            url += '&tabs='+subcontroller;

        controller = (subcontroller?subcontroller:controller);

        $(modal+' .delete-form').validate({
            ignore: [],
            rules: {
                id: {
                    required: true
                }
            },
            onsubmit: true,
            event: 'submit',
            submitHandler: function(form) {
                $.jmRequest({
                    handler: "submit",
                    url: url,
                    method: 'post',
                    form: $(form),
                    resetForm: true,
                    success:function(data){
                        $(modal).modal('hide');
                        if(data.statut) {
                            window.setTimeout(function() { $(".mc-message .alert-success").alert('close'); }, 4000);
                        } else {
                            window.setTimeout(function() { $(".mc-message .alert-warning").alert('close'); }, 4000);
                        }
                        $.jmRequest.notifier = {
                            cssClass : '.mc-message-'+controller
                        };
                        $.jmRequest.initbox(data.notify,{
                            display:true
                        });
                        if(data.statut && data.result) {
                            var ids = data.result.id.split(',');
                            for(var i = 0;i < ids.length; i++) {
                                $('#'+controller+'_' + ids[i]).remove();
                                if(!$('#table-'+controller+' tbody').children('tr').length) {
                                    $('#table-'+controller).addClass('hide').next('.no-entry').removeClass('hide');
                                }
                            }
                        }
                    }
                });
                return false;
            }
        });
    }

    /**
     * Initializes the modals and passes information to them
     */
    function initModalActions() {
        var modals = $('.modal');

        if(modals.length) {
            modals.modal({show: false});

            $('.modal_action').on('click',function(e){
                e.preventDefault();
                var modal = $(this).data('target');
                var controller = $(modal+' button[type="submit"]').val();

                switch (modal) {
                    case '#delete_modal':
                        if($(this).data('id') != undefined && $(this).data('id') != null) {
                            var id = $(this).data('id');
                            var sub = $(this).data('sub') ? $(this).data('sub') : false;
                            delete_data(modal, id, controller, sub);
                            break;
                        }
                    case '#delete_multiple_modal':
                        var selected = $('tbody tr .checkbox input[type="checkbox"]:checked');
                        if(selected.length) {
                            var ids = $.map(selected, function (v){ return $(v).val(); });
                            ids = ids.join();
                            var sub = $(this).data('sub') ? $(this).data('sub') : false;
                            delete_data(modal, ids, controller, sub);
                            break;
                        }
                    default:
                        $('#error_modal').modal('show');
                }

            });
        }
    }

    /**
     * Public method of tableForm object
     */
    return {
        // Public Functions
        run: function () {
            // Initialization of the multi-select checkboxes
            initCheckboxSelect();
            // Initialization of the modals
            //initModalActions();
        }
    }
})(jQuery);