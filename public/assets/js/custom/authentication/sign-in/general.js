"use strict";
var KTSigninGeneral = (function () {
    var e, t, i;
    return {
        init: function () {
            (e = document.querySelector("#kt_sign_in_form")),
                (t = document.querySelector("#kt_sign_in_submit")),
                (i = FormValidation.formValidation(e, {
                    fields: {
                        email: {
                            validators: {
                                regexp: {
                                    regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                    message: "El valor no es una dirección de correo electrónico válida",
                                },
                                notEmpty: {
                                    message: "Se requiere la dirección de correo electrónico",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "Se requiere la contraseña",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })),
                t.addEventListener("click", function (n) {
                    n.preventDefault(),
                        i.validate().then(function (i) {
                            "Valid" == i
                                ? (t.setAttribute("data-kt-indicator", "on"),
                                  (t.disabled = !0),
                                  setTimeout(function () {
                                      t.removeAttribute("data-kt-indicator"),
                                          (t.disabled = !1),
                                          Swal.fire({
                                              text: "Has iniciado sesión con éxito",
                                              icon: "success",
                                              buttonsStyling: !1,
                                              confirmButtonText: "Ok, ¡entendido!",
                                              customClass: {
                                                  confirmButton:
                                                      "btn btn-primary",
                                              },
                                          }).then(function (t) {
                                              if (t.isConfirmed) {
                                                  (e.querySelector(
                                                      '[name="email"]'
                                                  ).value = ""),
                                                      (e.querySelector(
                                                          '[name="password"]'
                                                      ).value = "");
                                                  var i = e.getAttribute(
                                                      "data-kt-redirect-url"
                                                  );
                                                  i && (location.href = i);
                                              }
                                          });
                                  }, 2e3))
                                : Swal.fire({
                                      text: "Lo sentimos, parece que se detectaron algunos errores. Por favor, inténtalo de nuevo.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, ¡entendido!",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});
