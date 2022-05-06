document.addEventListener('DOMContentLoaded', function (e) {

    const rule_name = {
        validators: {
            notEmpty: {
                message: 'Rule name is required'
            },
            stringLength: {
                min: 4,
                message: 'Rule name must be more than 3 characters'
            },
            regexp: {
                regexp: /^([a-zA-Z0-9!@#$%^&*().,<>{}[\]<>?_=+\-|;:\'\"\/]+\s?)*$/,
                message: 'Extra space are not allowed in rule name'
            },
        }
    };
    const profile = {
        validators: {
            notEmpty: {
                message: 'Profile is required'
            }
        }
    };
    const rule_ad_type = {
        validators: {
            notEmpty: {
                message: 'Advertising type is required'
            }
        }
    };
    const rule_select_type = {
        validators: {
            notEmpty: {
                message: 'Select type is required'
            }
        }
    };
    const rule_select_type_value = {
        validators: {
            notEmpty: {
                message: 'portfolio/campaign is required'
            },
        }
    };
    const look_back_period = {
        validators: {
            notEmpty: {
                message: 'Look back period is required'
            }
        }
    };
    const frequency = {
        validators: {
            notEmpty: {
                message: 'Frequency is required'
            }
        }
    };
    const metricsValidators = {
        validators: {
            notEmpty: {
                message: 'Metrics is required'
            }
        }
    };
    const conditionValidators = {
        validators: {
            notEmpty: {
                message: 'Condition is required'
            },
        }
    };
    const valueValidators = {
        validators: {
            notEmpty: {
                message: 'Value is required'
            },
            numeric: {
                message: 'Value must be a numeric number'
            }
        }
    };
    const bid_cpc_type = {
        validators: {
            notEmpty: {
                message: 'This field is required'
            }
        }
    };
    const bid = {
        validators: {
            notEmpty: {
                message: 'This field is required'
            }
        }
    };
    const bid_by_type = {
        validators: {
            notEmpty: {
                message: 'bid type is required'
            }
        }
    };
    const bid_by_value = {
        validators: {
            notEmpty: {
                message: 'Bid by value is required'
            },
            regexp: {
                regexp: /^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/,
                message: 'Bid by value must be a positive numeric number'
            },
        }
    };

    var validationForm = FormValidation.formValidation(
        document.getElementById('bidding_rule_form'),
        {
            fields: {
                rule_name: rule_name,
                profile: profile,
                rule_ad_type: rule_ad_type,
                rule_select_type: rule_select_type,
                'rule_select_type_value[]': rule_select_type_value,
                look_back_period: look_back_period,
                frequency: frequency,

                statement_metric: metricsValidators,
                statement_condition: conditionValidators,
                statement_value: valueValidators,

                bid_cpc_type: bid_cpc_type,
                bid: bid,
                bid_by_type: bid_by_type,
                bid_by_value: bid_by_value,
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                submitButton: new FormValidation.plugins.SubmitButton(),
            },
        }
    ); // end formValidation

    var editValidationForm = FormValidation.formValidation(
        document.getElementById('edit_bidding_rule_form'),
        {
            fields: {
                edit_rule_name: rule_name,
                edit_profile: profile,
                edit_rule_ad_type: rule_ad_type,
                edit_rule_select_type: rule_select_type,
                'edit_rule_select_type_value[]': rule_select_type_value,
                edit_look_back_period: look_back_period,
                edit_frequency: frequency,

                edit_statement_metric: metricsValidators,
                edit_statement_condition: conditionValidators,
                edit_statement_value: valueValidators,

                edit_bid_cpc_type: bid_cpc_type,
                edit_bid: bid,
                edit_bid_by_type: bid_by_type,
                edit_bid_by_value: bid_by_value,
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
        }
    ); // end formValidation

    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });// end on click

    //updating
    $(document).on('click', '.editRule', function () {
        var id = $(this).attr('id');
        $('#edit_rule_form_result').html('');
        $.ajax({
            url: base_url + "/biddingRule/" + id,
            dataType: "json",
            success: function (data) {
                //set user data in model and show model
                editValidationForm.resetForm(true);

                let rule = data.rule_info;
                removeExtraEditSelectFields();

                $('#edit_rule_name').val(rule['rule_name']);

                let html = ""
                let profiles = rule['profiles'];
                for (let count = 0; count < profiles.length; count++) {
                    if (rule['profile'] === profiles[count].id + "|" + profiles[count].profile_id) {
                        html += "<option selected='selected' value='" + profiles[count].id + "|" + profiles[count].profile_id + "'>" + profiles[count].name + "</option>";
                    } else {
                        html += "<option value='" + profiles[count].id + "|" + profiles[count].profile_id + "'>" + profiles[count].name + "</option>";
                    } // end else
                } // end for
                $('#edit_profile').html(html);

                $("input[name=edit_rule_ad_type]").val([rule['rule_ad_type']]);
                $("input[name=edit_rule_select_type]").val([rule['rule_select_type']]);

                let select_types = rule['select_types'];
                if (rule['rule_select_type'] === 'campaign') {
                    html = "<option value=''>Select Campaign </option>";
                    for (let count = 0; count < select_types.length; count++) {
                        html += "<option value='" + select_types[count].campaign_id + "'>" + select_types[count].name + "</option>";
                    } // end for
                } else {
                    html = "<option value=''>Select Portfolio</option>";
                    for (let count = 0; count < select_types.length; count++) {
                        html += "<option value='" + select_types[count].portfolios_id + "'>" + select_types[count].portfolios_name + "</option>";
                    } // end for
                } // end else
                $('#edit_rule_select_type_value').html(html);
                $('#edit_rule_select_type_value').val(rule['rule_select_type_value']);

                html = "<option selected value='0'>Select</option>";
                let pre_set_rules = rule['pre_set_rules'];
                for (let count = 0; count < pre_set_rules.length; count++) {
                    if (rule['fk_pre_set_rule_id'] == pre_set_rules[count].id) {
                        html += "<option selected='selected' value='" + pre_set_rules[count].id + "'>" + pre_set_rules[count].preset_name + "</option>";
                    } else {
                        html += "<option value='" + pre_set_rules[count].id + "'>" + pre_set_rules[count].preset_name + "</option>";
                    } // end else
                } // end for
                $('#edit_pre_set_rule').html(html);

                $('#edit_look_back_period').val(rule['look_back_period']);
                $('#edit_frequency').val(rule['frequency']);

                $('#edit_statement_metric').val(rule['statement_metric']);
                $('#edit_statement_condition').val(rule['statement_condition']);
                $('#edit_statement_value').val(rule['statement_value']);

                $('#edit_bid_cpc_type').val(rule['bid_cpc_type']);
                $('#edit_bid').val(rule['then_clause']);
                $("#edit_bid_by_type").val([rule['bid_by_type']]);
                $('#edit_bid_by_value').val(rule['bid_by_value']);

                emailList = data.emailList;
                for (let count = 0; count < emailList.length; count++) {
                    if (emailList[count] !== "") {
                        html += "<option value='" + emailList[count] + "'>" + emailList[count] + "</option>";
                    } // end if
                } // end for
                $('#edit_cc_emails').html(html);
                $('#edit_cc_emails').val(rule['cc_emails']);

                if (rule['and_or'] == "and" || rule['and_or'] == "or") {
                    addExtraEditSelectFields();

                    $("input[name=edit_rule_select2]").val([rule['and_or']]);

                    $('#edit_statement2_metric').val(rule['statement2_metric']);
                    $('#edit_statement2_condition').val(rule['statement2_condition']);
                    $('#edit_statement2_value').val(rule['statement2_value']);
                } // end if
                $('#hidden_id').val(rule['id']);
                $('#ruleModal').modal({ backdrop: 'static', keyboard: true });
            }  // end success
        })  // end ajax
    }); // end on click

    //delete rule
    $(document).on('click', '.deleteRule', function () {
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this rule!",
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'OK',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                //set CSRF Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: base_url + "/biddingRule/" + id,
                    type: "DELETE",
                    dataType: "json",
                    success: function (response) {
                        //Reload datatable
                        $('#bid_rule_history_table').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            type: "success",
                            title: 'Deleted!',
                            allowOutsideClick: false,
                            text: response.success,
                            confirmButtonClass: 'btn btn-success',
                        })
                    }  // end success
                }); // end ajax
            } // end if
        });  // end then
    }); // end on click

    $('#save_as_rule').on('click', function (event) {
        validationForm.disableValidator('profile');
        validationForm.disableValidator('rule_ad_type');
        validationForm.disableValidator('rule_select_type');
        validationForm.disableValidator('rule_select_type_value[]');

        validationForm.validate().then(function (status) {
            if (status == 'Valid') {

                let action_url = base_url + "/biddingRule/storePreSetRule";
                let action_method = 'POST';

                //ajax call
                $.ajax({
                    url: action_url,
                    type: action_method,
                    data: $('#bidding_rule_form').serialize(),
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        if (data.errors) {
                            Swal.fire({
                                title: "Failed",
                                text: data.errors,
                                allowOutsideClick: false,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        } // end if
                        if (data.success) {
                            getPreSetRules();
                            //Refresh datatable
                            Swal.fire({
                                title: "Done",
                                text: data.success,
                                allowOutsideClick: false,
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }); // end swal fire
                        } // end if
                    },  // end success
                }); // end ajax
            } // end if
        }); // end then form validation
    }); // end on click

    $('#save').on('click', function (event) {
        validationForm.enableValidator('profile');
        validationForm.enableValidator('rule_ad_type');
        validationForm.enableValidator('rule_select_type');
        validationForm.enableValidator('rule_select_type_value[]');
        validationForm.validate().then(function (status) {
            if (status == 'Valid') {

                $("#save").attr("disabled", true);
                $('#save').html('Creating & sending Email...');

                let action_url = base_url + "/biddingRule/storeRule";
                let action_method = 'POST';
                //ajax call
                $.ajax({
                    url: action_url,
                    type: action_method,
                    data: $('#bidding_rule_form').serialize(),
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        $("#save").attr("disabled", false);
                        $('#save').html('Save');
                        if (data.errors) {
                            Swal.fire({
                                title: "Failed",
                                text: data.errors,
                                allowOutsideClick: false,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }); // end swal fire
                        } // end if
                        if (data.success) {
                            validationForm.resetForm(true);
                            $('#bidding_rule_form')[0].reset();
                            $('#pre_set_rule').val('');
                            $('#cc_emails').html('');
                            $('#rule_select_type_value').html('');
                            $('#profile').val(null).trigger('change');
                            $('#old_rule').html('');
                            getEmailList();
                            //Refresh datatable
                            $('#bid_rule_history_table').DataTable().ajax.reload(null, false);

                            Swal.fire({
                                title: "Done",
                                text: data.success,
                                allowOutsideClick: false,
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }); // end swal fire
                        } // end if
                    }, // end success
                    error: function (xhr, httpStatusMessage, customErrorMessage) {
                        $("#save").attr("hidden", false);
                        $('#save').html('Save');
                        Swal.fire({
                            title: xhr.status + " Error",
                            text: customErrorMessage,
                            type: "info",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }); // end swal fire
                    } // end error
                }); // end ajax
            } // end if
        }); // end then
    }); // end on click

    $('#edit_save').on('click', function (event) {
        editValidationForm.enableValidator('edit_profile');
        editValidationForm.enableValidator('edit_rule_ad_type');
        editValidationForm.enableValidator('edit_rule_select_type');
        editValidationForm.validate().then(function (status) {
            if (status == 'Valid') {
                let action_url = base_url + "/biddingRule/" + $('#hidden_id').val();
                let action_method = 'PUT';
                //ajax call
                $.ajax({
                    url: action_url,
                    type: action_method,
                    data: $('#edit_bidding_rule_form').serialize(),
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        if (data.errors) {
                            var html = '';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<div class="alert alert-danger alert-dismissible fade show" role="alert">\n' +
                                    '            <p class="mb-0">' + data.errors[count] + '</p>' +
                                    '            <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                                    '                <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>\n' +
                                    '            </button>\n' +
                                    '        </div>';
                            } // end for
                            $('#edit_rule_form_result').html(html);
                        } // end if
                        if (data.success) {
                            editValidationForm.resetForm(true);
                            $('#edit_cc_emails').html('');
                            $('#edit_rule_select_type_value').html('');
                            //Refresh datatable
                            $('#bid_rule_history_table').DataTable().ajax.reload(null, false);
                            $('#ruleModal').modal('hide');
                            Swal.fire({
                                title: "Done",
                                text: data.success,
                                allowOutsideClick: false,
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }); // end swal fire
                        } // end if
                    }, // end success
                }); // end ajax
            } // end if
        }); // end then
    }); // end on click

    $("#profile").select2({
        width: '100%',
        dropdownParent: $("#bidding_rule_form"),
        placeholder: "Select Profile",
        sorter: function (data) {
            return data.sort(function (a, b) {
                return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
            }); // end return
        }, // end sorter
        language: {
            noResults: function (e) {
                return "No profile found";
            }, // end noResults
        } // end language
    }).on('change.select2', function () {
        // Re validate the profile field when an option is chosen
        validationForm.revalidateField('profile');
    });  // end on change select2

    getEmailList();

    $("#old_rule").select2({
        width: '100%',
        dropdownParent: $("#bidding_rule_form"),
        placeholder: "Select Rule For Preset Portfolio/Campaign",
        sorter: function (data) {
            return data.sort(function (a, b) {
                return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
            }); // end return
        }, // end sorter
        language: {
            noResults: function (e) {
                return "No rule found for preset portfolio/campaign";
            }, // end noResults
        } // end language
    }); // end select2

    $("#rule_select_type_value").select2({
        width: '100%',
        dropdownParent: $("#bidding_rule_form"),
        placeholder: "Select Portfolio/Campaign",
        language: {
            noResults: function (e) {
                return "No portfolio/campaign found";
            }, // end noResults
            maximumSelected: function (e) {
                return "Max " + e.maximum + " portfolio/campaign can be selected";
            }, // end maximumSelected
        }// end language
    }).on('change.select2', function () {
        $('#old_rule').val(0).trigger('change');
    });// end on change select2

    $("#cc_emails").select2({
        width: '100%',
        dropdownParent: $("#bidding_rule_form"),
        placeholder: "emails",
        tags: true,
        createTag: function (term, data) {
            let value = term.term;
            if (validateEmail(value)) {
                return {
                    id: value,
                    text: value
                };// end return
            }// end if
            return null;
        },// end createTag
        language: {
            noResults: function (e) {
                return "No email(s) found";
            }, // end noResults
            maximumSelected: function (e) {
                return "Max " + e.maximum + " email can be selected";
            },// end maximumSelected
        }// end language
    })// end select2

    $("#pre_set_rule").select2({
        width: '100%',
        dropdownParent: $("#bidding_rule_form"),
        placeholder: "Select Preset Rule",
        language: {
            noResults: function (e) {
                return "No preset rule found";
            }, // end noResults
        }// end language
    });// end select2

    $("#edit_profile").select2({
        width: '100%',
        dropdownParent: $("#edit_bidding_rule_form"),
        placeholder: "Select Profile",
        sorter: function (data) {
            return data.sort(function (a, b) {
                return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
            });// end return
        },// end sorter
        language: {
            noResults: function (e) {
                return "No profile found";
            }, // end noResults
        }// end language
    }).on('change.select2', function () {
        // Re validate the profile field when an option is chosen
        editValidationForm.revalidateField('edit_profile');
    }); // end on change select2

    $("#edit_rule_select_type_value").select2({
        width: '100%',
        dropdownParent: $("#edit_bidding_rule_form"),
        placeholder: "Select Portfolio/Campaign",
        language: {
            noResults: function (e) {
                return "No portfolio/campaign found";
            }, // end noResults
            maximumSelected: function (e) {
                return "Max " + e.maximum + " portfolio/campaign can be selected";
            },// end maximumSelected
        }// end language
    });// end select2

    $("#edit_cc_emails").select2({
        width: '100%',
        dropdownParent: $("#edit_bidding_rule_form"),
        placeholder: "emails",
        tags: true,
        createTag: function (term, data) {
            let value = term.term;
            if (validateEmail(value)) {
                return {
                    id: value,
                    text: value
                };// end return
            }// end if
            return null;
        },// end createTag
        language: {
            noResults: function (e) {
                return "No email(s) found";
            }, // end noResults
            maximumSelected: function (e) {
                return "Max " + e.maximum + " email can be selected";
            },// end maximumSelected
        }// end language
    });// end select2

    $("#edit_pre_set_rule").select2({
        width: '100%',
        dropdownParent: $("#edit_bidding_rule_form"),
        placeholder: "Select Preset Rule",
        language: {
            noResults: function (e) {
                return "No preset rule found";
            }, // end noResults
        }// end language
    });// end select2

    $('#add_more').on('click', function (event) {
        addExtraSelectFields();
    }); // end on click

    $('#edit_add_more').on('click', function (event) {
        addExtraEditSelectFields();
    }); // end on click

    $('#remove').on('click', function (event) {
        removeExtraSelectFields();
    }); // end on click

    $('#edit_remove').on('click', function (event) {
        removeExtraEditSelectFields();
    }); // end on click

    //change status in on checking or unchecking Checkbox
    $(document).on('click', '.status', function () {

        //get user and Status ID
        var id = $(this).attr('id');
        var status = $(this).val();
        //set CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } // end headers
        }); // end ajaxSetup
        var data = 'is_active=' + status;
        $.ajax({
            url: base_url + "/biddingRule/status/" + id,
            data: data,
            type: "PUT",
            dataType: "json",
            success: function (data) {
                //Reload datatable
                $('#bid_rule_history_table').DataTable().ajax.reload(null, false);
            } // end success
        })  // end ajax
    }); // end on click

    $('#profile').on('change', function (event) {
        let profile = $('#profile').val();
        let rule_ad_type = $('input[name="rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="rule_select_type"]:checked').val();
        setPortfolioOrCampaignList(profile, rule_ad_type, rule_select_type);
        getOldRuleList(profile, rule_ad_type);
    }); // end on change

    $('#old_rule').on('change', function (event) {
        let old_rule = $('#old_rule').val();
        setPreSetPortfolioCampaign(old_rule);
    }); // end on change

    $('#edit_profile').on('change', function (event) {
        let profile = $('#edit_profile').val();
        let rule_ad_type = $('input[name="edit_rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="edit_rule_select_type"]:checked').val();
        setPortfolioOrCampaignEditList(profile, rule_ad_type, rule_select_type);
    }); // end on change

    $('#pre_set_rule').on('change', function (event) {
        let pre_set_rule = $('#pre_set_rule').val();
        getPreSetRuleInfo(pre_set_rule);
    }); // end on change

    $('#edit_pre_set_rule').on('change', function (event) {
        let pre_set_rule = $('#edit_pre_set_rule').val();
        getEditPreSetRuleInfo(pre_set_rule);
    }); // end on change

    $("input[name='rule_ad_type']").change(function () {
        let profile = $('#profile').val();
        let rule_ad_type = $('input[name="rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="rule_select_type"]:checked').val();
        setPortfolioOrCampaignList(profile, rule_ad_type, rule_select_type);
        getOldRuleList(profile, rule_ad_type);
    }); // end on change

    $("input[name='edit_rule_ad_type']").change(function () {
        let profile = $('#edit_profile').val();
        let rule_ad_type = $('input[name="edit_rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="edit_rule_select_type"]:checked').val();
        setPortfolioOrCampaignEditList(profile, rule_ad_type, rule_select_type);
    }); // end on change

    $("input[name='rule_select_type']").change(function () {
        let profile = $('#profile').val();
        let rule_ad_type = $('input[name="rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="rule_select_type"]:checked').val();
        $('#old_rule').val(0).trigger('change');
        setPortfolioOrCampaignList(profile, rule_ad_type, rule_select_type);
    }); // end on change

    $("input[name='edit_rule_select_type']").change(function () {
        let profile = $('#edit_profile').val();
        let rule_ad_type = $('input[name="edit_rule_ad_type"]:checked').val();
        let rule_select_type = $('input[name="edit_rule_select_type"]:checked').val();
        setPortfolioOrCampaignEditList(profile, rule_ad_type, rule_select_type);
    }); // end on change

    function getOldRuleList(profile, rule_ad_type) {
        let data = 'profile=' + profile + '&rule_ad_type=' + rule_ad_type;
        if (typeof (rule_ad_type) != "undefined" && profile != null && typeof (profile) != "undefined") {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getOldRuleList",
                data: data,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    select_type = data.old_rule_list;
                    let html = "<option value='0' selected disabled>- Select Rule -</option>";
                    for (let count = 0; count < select_type.length; count++) {
                        html += "<option value='" + select_type[count].id + "'>" + select_type[count].rule_name + "</option>";
                    }  // end for
                    $('#old_rule').html(html);
                },  // end success
            });  // end ajax
        }  // end if
    }  // end function

    function setPortfolioOrCampaignList(profile, rule_ad_type, rule_select_type) {
        let data = 'profile=' + profile + '&rule_ad_type=' + rule_ad_type + '&rule_select_type=' + rule_select_type;
        if (typeof (rule_ad_type) != "undefined" && typeof (rule_select_type) != "undefined" && profile != null && typeof (profile) != "undefined") {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getPortfolioOrCampaignList",
                data: data,
                type: "PUT",
                dataType: "json",
                success: function (data) {
                    select_type = data.select_type;
                    let html = "";
                    if (rule_select_type === 'campaign') {
                        html = "<option value=''>Select campaign </option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].campaign_id + "'>" + select_type[count].name + "</option>";
                        }  // end for
                    } else {
                        html = "<option value=''>Select portfolio</option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].portfolios_id + "'>" + select_type[count].portfolios_name + "</option>";
                        }  // end for
                    }  // end else
                    $('#rule_select_type_value').html(html);
                },  // end success
            });  // end ajax
        }  // end if
    }  // end function

    function setPortfolioOrCampaignEditList(profile, rule_ad_type, rule_select_type, selected_values = []) {
        let data = 'profile=' + profile + '&rule_ad_type=' + rule_ad_type + '&rule_select_type=' + rule_select_type;
        if (typeof (rule_ad_type) != "undefined" && typeof (rule_select_type) != "undefined" && profile != null && typeof (profile) != "undefined") {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getPortfolioOrCampaignList",
                data: data,
                type: "PUT",
                dataType: "json",
                success: function (data) {
                    select_type = data.select_type;
                    let html = "";
                    if (rule_select_type === 'campaign') {
                        html = "<option value=''>Select campaign </option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].campaign_id + "'>" + select_type[count].name + "</option>";
                        }  // end for
                    } else {
                        html = "<option value=''>Select portfolio</option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].portfolios_id + "'>" + select_type[count].portfolios_name + "</option>";
                        }  // end for
                    }  // end else
                    $('#edit_rule_select_type_value').html(html);
                    $('#edit_rule_select_type_value').val(selected_values);
                },  // end success
            });  // end ajax
        }  // end if
    }  // end function

    function setPreSetPortfolioCampaign(old_rule) {
        let data = 'old_rule=' + old_rule;
        if (typeof (old_rule) != "undefined" && old_rule != null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getPreSetPortfolioOrCampaignList",
                data: data,
                type: "PUT",
                dataType: "json",
                success: function (data) {
                    select_type = data.select_type;
                    let rule_select_type = data.rule_select_type;
                    let rule_select_type_value = data.rule_select_type_value;

                    $("input[name=rule_select_type]").val([rule_select_type]);

                    let html = "";
                    if (rule_select_type === 'campaign') {
                        html = "<option value=''>Select campaign </option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].campaign_id + "'>" + select_type[count].name + "</option>";
                        }  // end for
                    } else {
                        html = "<option value=''>Select portfolio</option>";
                        for (let count = 0; count < select_type.length; count++) {
                            html += "<option value='" + select_type[count].portfolios_id + "'>" + select_type[count].portfolios_name + "</option>";
                        }  // end for
                    }  // end else
                    $('#rule_select_type_value').html(html);
                    $('#rule_select_type_value').val(rule_select_type_value);
                },  // end success
            });  // end ajax
        }  // end if
    }  // end function
    function getEmailList() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }  // end headers
        });  // end ajaxSetup
        $.ajax({
            url: base_url + "/biddingRule/getEmailList",
            type: "PUT",
            dataType: "json",
            success: function (data) {
                emailList = data.emailList;
                let html = "";
                for (let count = 0; count < emailList.length; count++) {
                    html += "<option value='" + emailList[count] + "'>" + emailList[count] + "</option>";
                }  // end for
                $('#cc_emails').html(html);
            },  // end success
        });  // end ajax
    }  // end function

    function getPreSetRules() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }  // end headers
        });  // end ajaxSetup
        $.ajax({
            url: base_url + "/biddingRule/getPreSetRules",
            type: "GET",
            dataType: "json",
            success: function (data) {
                let preset_rule = data.preset_rule;
                let html = "<option value='0'>Select</option>";
                for (let count = 0; count < preset_rule.length; count++) {
                    html += "<option value='" + preset_rule[count].id + "'>" + preset_rule[count].preset_name + "</option>";
                }  // end for
                $('#pre_set_rule').html(html);
            }  // end headers
        })  // end success
    }  // end function

    function getPreSetRuleInfo(pre_set_rule) {
        let data = 'pre_set_rule=' + pre_set_rule;
        if (pre_set_rule == 0) {
            removeExtraSelectFields();
            $('#look_back_period').val("");
            $('#frequency').val("");

            $('#statement_metric').val("");
            $('#statement_condition').val("");
            $('#statement_value').val("");

            $('#bid').val("");
            $('#bid_by_value').val("");

        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getPreSetRuleInfo",
                type: "PUT",
                data: data,
                dataType: "json",
                success: function (data) {
                    let rule = data.preset_rule;
                    removeExtraSelectFields();
                    $('#look_back_period').val(rule.look_back_period + "|" + rule.look_back_period_days);
                    $('#frequency').val(rule.frequency);

                    $('#statement_metric').val(rule.metric);
                    $('#statement_condition').val(rule.condition);
                    $('#statement_value').val(rule.integer_values);

                    $('#bid_cpc_type').val(rule.bid_cpc_type);
                    $('#bid').val(rule.then_clause);
                    $("#bid_by_type").val([rule.bid_by_type]);
                    $('#bid_by_value').val(rule.bid_by_value);

                    if (rule.and_or != "NA") {
                        addExtraSelectFields();
                        let metric = (rule.metric).split(",");
                        let condition = (rule.condition).split(",");
                        let integer_values = (rule.integer_values).split(",");

                        $('#statement_metric').val(metric[0]);
                        $('#statement_condition').val(condition[0]);
                        $('#statement_value').val(integer_values[0]);

                        $("input[name=rule_select2]").val([rule.and_or]);

                        $('#statement2_metric').val(metric[1]);
                        $('#statement2_condition').val(condition[1]);
                        $('#statement2_value').val(integer_values[1]);

                    }  // end if
                }  // end success
            })  // end ajax
        }  // end else
    }  // end function

    function getEditPreSetRuleInfo(pre_set_rule) {
        let data = 'pre_set_rule=' + pre_set_rule;
        if (pre_set_rule == 0) {

            removeExtraEditSelectFields();
            $('#edit_look_back_period').val("");
            $('#edit_frequency').val("");

            $('#edit_statement_metric').val("");
            $('#edit_statement_condition').val("");
            $('#edit_statement_value').val("");

            $('#edit_bid_cpc_type').val("");
            $('#edit_bid').val("");
            $('#edit_bid_by_type').val("");
            $('#edit_bid_by_value').val("");

        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }  // end headers
            });  // end ajaxSetup
            $.ajax({
                url: base_url + "/biddingRule/getPreSetRuleInfo",
                type: "PUT",
                data: data,
                dataType: "json",
                success: function (data) {
                    let rule = data.preset_rule;
                    removeExtraEditSelectFields();
                    $('#edit_look_back_period').val(rule.look_back_period + "|" + rule.look_back_period_days);
                    $('#edit_frequency').val(rule.frequency);

                    $('#edit_statement_metric').val(rule.metric);
                    $('#edit_statement_condition').val(rule.condition);
                    $('#edit_statement_value').val(rule.integer_values);

                    $('#edit_bid_cpc_type').val(rule.bid_cpc_type);
                    $('#edit_bid').val(rule.then_clause);
                    $("#edit_bid_by_type").val([rule.bid_by_type]);
                    $('#edit_bid_by_value').val(rule.bid_by_value);

                    if (rule.and_or != "NA") {
                        addExtraEditSelectFields();
                        let metric = (rule.metric).split(",");
                        let condition = (rule.condition).split(",");
                        let integer_values = (rule.integer_values).split(",");

                        $('#edit_statement_metric').val(metric[0]);
                        $('#edit_statement_condition').val(condition[0]);
                        $('#edit_statement_value').val(integer_values[0]);

                        $("input[name=edit_rule_select2]").val([rule.and_or]);

                        $('#edit_statement2_metric').val(metric[1]);
                        $('#edit_statement2_condition').val(condition[1]);
                        $('#edit_statement2_value').val(integer_values[1]);

                    }  // end if
                }  // end success
            })  // end ajax
        }  // end else
    }  // end function

    function addExtraSelectFields() {
        $('[data-name="temp_statement2_metric"]').attr({ 'id': 'statement2_metric', 'name': 'statement2_metric' });
        $('[data-name="temp_statement2_condition"]').attr({
            'id': 'statement2_condition',
            'name': 'statement2_condition'
        });
        $('[data-name="temp_statement2_value"]').attr({ 'id': 'statement2_value', 'name': 'statement2_value' });

        $('#statement2_metric').val("");
        $('#statement2_condition').val("");
        $('#statement2_value').val("");

        if ($('#add_more_exist').val() == 0) {
            validationForm.addField('statement2_metric', metricsValidators)
                .addField('statement2_condition', conditionValidators)
                .addField('statement2_value', valueValidators);
        }  // end if

        $('#add_move_statement_div').attr("hidden", false);
        $('#add_more_button_div').attr("hidden", true);
        $('#add_more_exist').val(1);

        validationForm.validate();
    }  // end function

    function addExtraEditSelectFields() {

        $('[data-name="edit_temp_statement2_metric"]').attr({
            'id': 'edit_statement2_metric',
            'name': 'edit_statement2_metric'
        });
        $('[data-name="edit_temp_statement2_condition"]').attr({
            'id': 'edit_statement2_condition',
            'name': 'edit_statement2_condition'
        });
        $('[data-name="edit_temp_statement2_value"]').attr({
            'id': 'edit_statement2_value',
            'name': 'edit_statement2_value'
        });

        $('#edit_statement2_metric').val("");
        $('#edit_statement2_condition').val("");
        $('#edit_statement2_value').val("");

        if ($('#edit_add_more_exist').val() == 0) {
            editValidationForm.addField('edit_statement2_metric', metricsValidators)
                .addField('edit_statement2_condition', conditionValidators)
                .addField('edit_statement2_value', valueValidators);
        }  // end if

        $('#edit_add_move_statement_div').attr("hidden", false);
        $('#edit_add_more_button_div').attr("hidden", true);
        $('#edit_add_more_exist').val(1);

        editValidationForm.validate();
    }  // end function

    function removeExtraSelectFields() {

        if ($('#add_more_exist').val() == 1) {
            validationForm.removeField('statement2_metric')
                .removeField('statement2_condition')
                .removeField('statement2_value');
        }  // end if

        $('[data-name="temp_statement2_metric"]').attr({
            'id': 'temp_statement2_metric',
            'name': 'temp_statement2_metric'
        });
        $('[data-name="temp_statement2_condition"]').attr({
            'id': 'temp_statement2_condition',
            'name': 'temp_statement2_condition'
        });
        $('[data-name="temp_statement2_value"]').attr({ 'id': 'temp_statement2_value', 'name': 'temp_statement2_value' });

        $('#add_move_statement_div').attr("hidden", true);
        $('#add_more_button_div').attr("hidden", false);

        $('#add_more_exist').val(0);
        validationForm.validate();
    }  // end function

    function removeExtraEditSelectFields() {

        if ($('#edit_add_more_exist').val() == 1) {
            editValidationForm.removeField('edit_statement2_metric')
                .removeField('edit_statement2_condition')
                .removeField('edit_statement2_value');
        }  // end if

        $('[data-name="edit_temp_statement2_metric"]').attr({
            'id': 'edit_temp_statement2_metric',
            'name': 'edit_temp_statement2_metric'
        });
        $('[data-name="edit_temp_statement2_condition"]').attr({
            'id': 'edit_temp_statement2_condition',
            'name': 'edit_temp_statement2_condition'
        });
        $('[data-name="edit_temp_statement2_value"]').attr({
            'id': 'edit_temp_statement2_value',
            'name': 'edit_temp_statement2_value'
        });

        $('#edit_add_move_statement_div').attr("hidden", true);
        $('#edit_add_more_button_div').attr("hidden", false);

        $('#edit_add_more_exist').val(0);

        editValidationForm.validate();
    }  // end function

    function validateEmail(email) {
        let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }  // end function
});



