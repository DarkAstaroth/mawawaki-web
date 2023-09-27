"use strict";
var KTSignupGeneral = (function () {
    var e,
        t,
        a,
        r,
        s = function () {
            return 100 === r.getScore();
        };
    return {
        init: function () {
            (e = document.querySelector("#kt_sign_up_form")),
                (t = document.querySelector("#kt_sign_up_submit")),
                (r = KTPasswordMeter.getInstance(
                    e.querySelector('[data-kt-password-meter="true"]')
                )),
                (a = FormValidation.formValidation(e, {
                    fields: {
                        "first-name": {
                            validators: {
                                notEmpty: {
                                    message: "El Nombre es obligatorio",
                                },
                            },
                        },
                        "last-name": {
                            validators: {
                                notEmpty: {
                                    message: "El Apellido es obligatorio",
                                },
                            },
                        },
                        email: {
                            validators: {
                                regexp: {
                                    regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                    message:
                                        "El valor no es una dirección de correo electrónico válida",
                                },
                                notEmpty: {
                                    message:
                                        "La dirección de correo electrónico es obligatoria",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "La contraseña es obligatoria",
                                },
                                callback: {
                                    message:
                                        "Por favor, ingrese una contraseña válida",
                                    callback: function (e) {
                                        if (e.value.length > 0) return s();
                                    },
                                },
                            },
                        },
                        "password_confirmation": {
                            validators: {
                                notEmpty: {
                                    message:
                                        "La confirmación de contraseña es obligatoria",
                                },
                                identical: {
                                    compare: function () {
                                        return e.querySelector(
                                            '[name="password"]'
                                        ).value;
                                    },
                                    message:
                                        "La contraseña y su confirmación no coinciden",
                                },
                            },
                        },
                        toc: {
                            validators: {
                                notEmpty: {
                                    message:
                                        "Debe aceptar los términos y condiciones",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger({
                            event: { password: !1 },
                        }),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })),
                t.addEventListener("click", function (s) {
                    s.preventDefault(),
                        a.revalidateField("password"),
                        a.validate().then(function (a) {
                            "Valid" == a
                                ? (t.setAttribute("data-kt-indicator", "on"),
                                  (t.disabled = !0),
                                  setTimeout(function () {
                                      t.removeAttribute("data-kt-indicator"),
                                          (t.disabled = !1),
                                          Swal.fire({
                                              text: "Ha restablecido su contraseña con éxito",
                                              icon: "success",
                                              buttonsStyling: !1,
                                              confirmButtonText:
                                                  "Ok, entendido",
                                              customClass: {
                                                  confirmButton:
                                                      "btn btn-primary",
                                              },
                                          }).then(function (t) {
                                              if (t.isConfirmed) {
                                                  e.reset(), r.reset();
                                                  var a = e.getAttribute(
                                                      "data-kt-redirect-url"
                                                  );
                                                  a && (location.href = a);
                                              }
                                          });
                                  }, 1500))
                                : Swal.fire({
                                      text: "Lo sentimos, parece que se detectaron algunos errores, por favor, inténtelo de nuevo.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, entendido",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                }),
                e
                    .querySelector('input[name="password"]')
                    .addEventListener("input", function () {
                        this.value.length > 0 &&
                            a.updateFieldStatus("password", "NotValidated");
                    });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTSignupGeneral.init();
});
