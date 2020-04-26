(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/UserForm.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/UserForm.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuelidate */ "./node_modules/vuelidate/lib/index.js");
/* harmony import */ var vuelidate__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vuelidate__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuelidate/lib/validators */ "./node_modules/vuelidate/lib/validators/index.js");
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  mixins: [vuelidate__WEBPACK_IMPORTED_MODULE_0__["validationMixin"]],
  validations: {
    form: {
      name: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        maxLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["maxLength"])(30)
      },
      email: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        email: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["email"]
      },
      password: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        minLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["minLength"])(8)
      },
      passwordConfirm: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        minLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["minLength"])(8)
      },
      roles: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"]
      }
    }
  },
  props: {
    dataForm: {
      type: Object,
      "default": function _default() {
        return {
          valid: false,
          name: '',
          email: '',
          roles: '',
          password: '',
          passwordConfirm: ''
        };
      }
    },
    submitText: {
      type: String,
      "default": 'Save'
    },
    submit: {
      type: Function,
      "default": function _default(data) {}
    }
  },
  data: function data() {
    return {
      valid: false,
      roles: [{
        value: 'Super Admin',
        text: 'Super Admin'
      }, {
        value: 'Admin',
        text: 'Admin'
      }, {
        value: 'User',
        text: 'User'
      }],
      form: {
        name: '',
        email: '',
        roles: '',
        password: '',
        passwordConfirm: ''
      }
    };
  },
  created: function created() {
    // bind with props
    this.form.name = this.dataForm.name;
    this.form.email = this.dataForm.email;
    this.form.roles = this.dataForm.roles.length > 0 ? this.dataForm.roles[0] : null;
    this.form.password = this.dataForm.password;
    this.form.passwordConfirm = this.dataForm.passwordConfirm;
  },
  computed: {
    nameErrors: function nameErrors() {
      var errors = [];
      if (!this.$v.form.name.$dirty) return errors;
      !this.$v.form.name.maxLength && errors.push('Name must be at most 30 characters long');
      !this.$v.form.name.required && errors.push('Name is required.');
      return errors;
    },
    emailErrors: function emailErrors() {
      var errors = [];
      if (!this.$v.form.email.$dirty) return errors;
      !this.$v.form.email.email && errors.push('Must be valid email');
      !this.$v.form.email.required && errors.push('Email is required');
      return errors;
    },
    rolesErrors: function rolesErrors() {
      var errors = [];
      if (!this.$v.form.roles.$dirty) return errors;
      !this.$v.form.roles.required && errors.push('Role is required');
      return errors;
    },
    passwordErrors: function passwordErrors() {
      var errors = [];
      if (!this.$v.form.password.$dirty) return errors;
      !this.$v.form.password.minLength && errors.push('Password must be atleast 8 characters long');
      !this.$v.form.password.required && errors.push('Password is required.');
      return errors;
    },
    passwordConfirmationErrors: function passwordConfirmationErrors() {
      var errors = [];
      if (!this.$v.form.passwordConfirm.$dirty) return errors;
      !this.$v.form.passwordConfirm.minLength && errors.push('Password confirmation must be atleast 8 characters long');
      !this.$v.form.passwordConfirm.required && errors.push('Password confirmation is required.');
      this.form.password != this.form.passwordConfirm && errors.push('Password confirmation does not match.');
      return errors;
    }
  },
  methods: {
    onSubmit: function onSubmit(e) {
      e.preventDefault();
      this.$v.form.$touch();
      setTimeout(function () {
        if (this.valid) {
          // submit to UserAdd or UserEdit
          this.submit(this.form);
        } else {
          _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', 'Failed! Please try again.');
        }
      }.bind(this), 100);
    },
    clear: function clear() {
      this.$refs.form.reset();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381& ***!
  \*****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-form",
    {
      ref: "form",
      staticClass: "p-3 pt-0",
      on: { submit: _vm.onSubmit },
      model: {
        value: _vm.valid,
        callback: function($$v) {
          _vm.valid = $$v
        },
        expression: "valid"
      }
    },
    [
      _c("v-text-field", {
        attrs: {
          "error-messages": _vm.nameErrors,
          counter: 30,
          label: "Name",
          required: ""
        },
        on: {
          input: function($event) {
            return _vm.$v.form.name.$touch()
          },
          blur: function($event) {
            return _vm.$v.form.name.$touch()
          }
        },
        model: {
          value: _vm.form.name,
          callback: function($$v) {
            _vm.$set(_vm.form, "name", $$v)
          },
          expression: "form.name"
        }
      }),
      _vm._v(" "),
      _c("v-text-field", {
        attrs: {
          "error-messages": _vm.emailErrors,
          label: "E-mail",
          required: ""
        },
        on: {
          input: function($event) {
            return _vm.$v.form.email.$touch()
          },
          blur: function($event) {
            return _vm.$v.form.email.$touch()
          }
        },
        model: {
          value: _vm.form.email,
          callback: function($$v) {
            _vm.$set(_vm.form, "email", $$v)
          },
          expression: "form.email"
        }
      }),
      _vm._v(" "),
      _c("v-select", {
        attrs: {
          items: _vm.roles,
          "error-messages": _vm.rolesErrors,
          rules: [
            function(v) {
              return !!v || "Role is required"
            }
          ],
          label: "Role"
        },
        model: {
          value: _vm.form.roles,
          callback: function($$v) {
            _vm.$set(_vm.form, "roles", $$v)
          },
          expression: "form.roles"
        }
      }),
      _vm._v(" "),
      _c("v-text-field", {
        attrs: {
          type: "password",
          name: "password",
          label: "Password",
          hint: "At least 8 characters",
          counter: "",
          "error-messages": _vm.passwordErrors
        },
        on: {
          input: function($event) {
            return _vm.$v.form.password.$touch()
          },
          blur: function($event) {
            return _vm.$v.form.password.$touch()
          }
        },
        model: {
          value: _vm.form.password,
          callback: function($$v) {
            _vm.$set(_vm.form, "password", $$v)
          },
          expression: "form.password"
        }
      }),
      _vm._v(" "),
      _c("v-text-field", {
        attrs: {
          type: "password",
          name: "password",
          label: "Password Confirmation",
          hint: "At least 8 characters",
          counter: "",
          "error-messages": _vm.passwordConfirmationErrors
        },
        on: {
          input: function($event) {
            return _vm.$v.form.passwordConfirm.$touch()
          },
          blur: function($event) {
            return _vm.$v.form.passwordConfirm.$touch()
          }
        },
        model: {
          value: _vm.form.passwordConfirm,
          callback: function($$v) {
            _vm.$set(_vm.form, "passwordConfirm", $$v)
          },
          expression: "form.passwordConfirm"
        }
      }),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "mt-3" },
        [
          _c(
            "v-btn",
            {
              staticClass: "mr-4",
              attrs: { type: "submit", small: "", color: "success" }
            },
            [_vm._v(_vm._s(_vm.submitText))]
          ),
          _vm._v(" "),
          _c("v-btn", { attrs: { small: "" }, on: { click: _vm.clear } }, [
            _vm._v("clear")
          ]),
          _vm._v(" "),
          _c(
            "v-btn",
            {
              staticClass: "float-right",
              attrs: { small: "", to: { name: "user list" } }
            },
            [_vm._v("Close")]
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/users/UserForm.vue":
/*!****************************************************!*\
  !*** ./resources/js/components/users/UserForm.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UserForm.vue?vue&type=template&id=070b1381& */ "./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381&");
/* harmony import */ var _UserForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UserForm.vue?vue&type=script&lang=js& */ "./resources/js/components/users/UserForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _UserForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/users/UserForm.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/users/UserForm.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/users/UserForm.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./UserForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/UserForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./UserForm.vue?vue&type=template&id=070b1381& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/users/UserForm.vue?vue&type=template&id=070b1381&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserForm_vue_vue_type_template_id_070b1381___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);