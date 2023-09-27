"use strict";
var KTAuthNuevaContraseña = (function () {
    var t,
        e,
        r,
        o,
        a = function () {
            return 100 === o.getScore();
        };
    return {
        init: function () {
            (t = document.querySelector("#kt_new_password_form")),
                (e = document.querySelector("#kt_new_password_submit")),
                (o = KTPasswordMeter.getInstance(
                    t.querySelector('[data-kt-password-meter="true"]')
                )),
                (r = FormValidation.formValidation(t, {
                    fields: {
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "La contraseña es requerida",
                                },
                                callback: {
                                    message:
                                        "Por favor, ingresa una contraseña válida",
                                    callback: function (t) {
                                        if (t.value.length > 0) return a();
                                    },
                                },
                            },
                        },
                        password_confirmation: {
                            validators: {
                                notEmpty: {
                                    message:
                                        "La confirmación de la contraseña es requerida",
                                },
                                identical: {
                                    compare: function () {
                                        return t.querySelector(
                                            '[name="password"]'
                                        ).value;
                                    },
                                    message:
                                        "La contraseña y su confirmación no son iguales",
                                },
                            },
                        },
                        toc: {
                            validators: {
                                notEmpty: {
                                    message:
                                        "Debes aceptar los términos y condiciones",
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
                e.addEventListener("click", function (a) {
                    a.preventDefault(),
                        r.revalidateField("password"),
                        r.validate().then(function (r) {
                            "Valid" == r
                                ? (e.setAttribute("data-kt-indicator", "on"),
                                  (e.disabled = !0),
                                  setTimeout(function () {
                                      e.removeAttribute("data-kt-indicator"),
                                          (e.disabled = !1),
                                          Swal.fire({
                                              text: "Has restablecido tu contraseña con éxito",
                                              icon: "success",
                                              buttonsStyling: !1,
                                              confirmButtonText:
                                                  "Ok, ¡entendido!",
                                              customClass: {
                                                  confirmButton:
                                                      "btn btn-primary",
                                              },
                                          }).then(function (e) {
                                              if (e.isConfirmed) {
                                                  (t.querySelector(
                                                      '[name="password"]'
                                                  ).value = ""),
                                                      (t.querySelector(
                                                          '[name="password_confirmation"]'
                                                      ).value = ""),
                                                      o.reset();
                                                  var r = t.getAttribute(
                                                      "data-kt-redirect-url"
                                                  );
                                                  r && (location.href = r);
                                              }
                                          });
                                  }, 1500))
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
                }),
                t
                    .querySelector('input[name="password"]')
                    .addEventListener("input", function () {
                        this.value.length > 0 &&
                            r.updateFieldStatus("password", "NotValidated");
                    });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTAuthNuevaContraseña.init();
});
