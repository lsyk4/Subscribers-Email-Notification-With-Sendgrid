jQuery( document ).ready( function ( $ ) {
    $('.sen-form-container form button').click(function(){
      post_via_ajax();
    });

    $('.sen-form-container form').submit(function(event){
      event.preventDefault();
    });

    function returnNewSubscriber(){
        var newSubscriberValue = $('.sen-form-container form .email').val();
        return newSubscriberValue;
    }
    function returnNewSubscriberNonce(){
        var newSubscriberNonceValue = $('.sen-form-container form #sen_subscribe_form').val();
        return newSubscriberNonceValue;
    }

    function post_via_ajax(){
        var new_subscriber_ajax_url = new_subscriber_params.new_subscriber_ajax_url;
        var sen_rns = returnNewSubscriber();
        if(sen_rns != "" && sen_rns != " " && sen_rns.indexOf("@") != -1){
          $.ajax({
              type: 'POST',
              url: new_subscriber_ajax_url,
              data: {
                  action: 'sen_new_subscriber',
                  email: returnNewSubscriber(),
                  sen_subscribe_form: returnNewSubscriberNonce()
              },
              beforeSend: function ()
              {
                  $(".sen-form-container .success-message").html('Sending');
              },
              success: function(data)
              {
                  $(".sen-form-container .success-message").html('Subscribed!');
                  $(".sen-subscribe-form").fadeOut();
              },
              error: function()
              {
                  $(".sen-form-container .success-message").html('Error. Please try again');

              }
          })
        }else{
          $(".sen-form-container .success-message").html('Error. Please enter a valid email address');
        }
    }
});
