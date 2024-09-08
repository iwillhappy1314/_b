import MicroModal from 'micromodal';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import '@sweetalert2/theme-dark/dark.css';

document.body.addEventListener('htmx:confirm', function(evt) {
  if (evt.target.matches('[confirm-with-sweet-alert=\'true\']')) {
    evt.preventDefault();
    console.log(evt);
    Swal.fire({
      title            : false,
      text             : `${evt.detail.question}`,
      icon             : 'info',
      buttons          : true,
      showCancelButton : true,
      theme            : 'dark',
      animation        : false,
      allowOutsideClick: false,
      dangerMode       : false,
    }).then((confirmed) => {
      if (confirmed.isConfirmed === true) {
        evt.detail.issueRequest();
      }
    });
  }
});

/**
 * 点击绑定TikTok UID的操作
 */
jQuery(document).ready(function($) {
  const loading = '<svg width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_EUy1{animation:spinner_grm3 1.2s infinite}.spinner_f6oS{animation-delay:.1s}.spinner_g3nX{animation-delay:.2s}.spinner_nvEs{animation-delay:.3s}.spinner_MaNM{animation-delay:.4s}.spinner_4nle{animation-delay:.5s}.spinner_ZETM{animation-delay:.6s}.spinner_HXuO{animation-delay:.7s}.spinner_YaQo{animation-delay:.8s}.spinner_GOx1{animation-delay:.9s}.spinner_4vv9{animation-delay:1s}.spinner_NTs9{animation-delay:1.1s}.spinner_auJJ{transform-origin:center;animation:spinner_T3O6 6s linear infinite}@keyframes spinner_grm3{0%,50%{animation-timing-function:cubic-bezier(.27,.42,.37,.99);r:1px}25%{animation-timing-function:cubic-bezier(.53,0,.61,.73);r:2px}}@keyframes spinner_T3O6{0%{transform:rotate(360deg)}100%{transform:rotate(0deg)}}</style><g class="spinner_auJJ"><circle class="spinner_EUy1" cx="12" cy="3" r="1"/><circle class="spinner_EUy1 spinner_f6oS" cx="16.50" cy="4.21" r="1"/><circle class="spinner_EUy1 spinner_NTs9" cx="7.50" cy="4.21" r="1"/><circle class="spinner_EUy1 spinner_g3nX" cx="19.79" cy="7.50" r="1"/><circle class="spinner_EUy1 spinner_4vv9" cx="4.21" cy="7.50" r="1"/><circle class="spinner_EUy1 spinner_nvEs" cx="21.00" cy="12.00" r="1"/><circle class="spinner_EUy1 spinner_GOx1" cx="3.00" cy="12.00" r="1"/><circle class="spinner_EUy1 spinner_MaNM" cx="19.79" cy="16.50" r="1"/><circle class="spinner_EUy1 spinner_YaQo" cx="4.21" cy="16.50" r="1"/><circle class="spinner_EUy1 spinner_4nle" cx="16.50" cy="19.79" r="1"/><circle class="spinner_EUy1 spinner_HXuO" cx="7.50" cy="19.79" r="1"/><circle class="spinner_EUy1 spinner_ZETM" cx="12" cy="21" r="1"/></g></svg>';

  const submitForm = function(formId, path) {

    const form = $(formId);
    const button = $(this).find('button[type=submit]');

    $.ajax({
      type      : 'POST',
      dataType  : 'json',
      url       : wpTiktokAffiliateFrontendSettings.home_url + path,
      data      : form.serialize(),
      beforeSend: function() {
        button.prepend('<i class="rsicon-spinner icon--loading mr-2">');
      },
      success   : function(response) {
        button.find('i').remove();

        if (response.success === true) {

          $('#tk-modal-icon').addClass('rsicon-check');

          $('#tk-modal-title').text(response.data.message);
          $('#tk-modal-content').text(response.data.description);

          MicroModal.show('tk-modal');

          if (response.data.url) {
            $('#tk-modal-close').removeAttr('data-micromodal-close').click(function() {
              window.location = response.data.url;
            });
          }
        } else {
          const errors = response.data.errors;
          form.find('.tk-form-errors').remove();

          $.each(errors, function(field, messages) {
            $('<div class="tk-form-errors text-sm text-primary mt-1 mb-2">' + messages + '</div>').insertAfter('[name=' + field + ']');
          });
        }

      },
      error     : function(data) {
        button.find('svg').remove();
      },
    });

  };

  $('#tk-bind-form').on('submit', function(event) {
    event.preventDefault();

    submitForm('#tk-bind-form', '/account/tiktok/bind');

    return false;
  });


  $('body').on('click', '.rs-button--close', function() {
    $('#htmx-modal').hide();
  });

});