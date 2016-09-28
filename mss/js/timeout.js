var username = '';
var url = location.origin + '/';
var timeout = 7200000; // 2 hours
var next_timeout = Date.now()+timeout;
var modal = $(
    '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">' +
        '<div class="modal-dialog modal-sm">' +
            '<div class="modal-content">' +
                '<div class="modal-header">' +
                    '<h5 class="modal-title"></h5>' +
                '</div>' +
                '<div class="modal-body" style="text-align: center">' +
                '<input id="timeout-password" placeholder="password" class="form-control" type="password" /><br>' +
                '<button class="btn btn-info" id="timeout-login">Log in</button>' +
                '</div>' +
            '</div>' +
        '</div>' +
    '</div>'
);
/*getting the username*/
$.get(url + 'user/get_username',{},function(data){
    if(data != ''){
        username = data;
        modal.find('.modal-title').css('text-align','center').html('Hi <b>'+username+'</b>,<br>are you still there? ');
    }
});
$(window).focus(function(){
    if(Date.now() > next_timeout){
        modal.modal({backdrop: 'static'})
    }
});

modal.find("#timeout-login").click(function(){
    $.post(url + 'user/user_login',{
        username: username,
        password: modal.find("#timeout-password").val()
    },function(data){
        location.reload();
    });
});
