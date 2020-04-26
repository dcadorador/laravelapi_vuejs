(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AccountForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AccountForm */ "./resources/js/components/accounts/AccountForm.vue");
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
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
  components: {
    AccountForm: _AccountForm__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      isSubmit: false
    };
  },
  methods: {
    onAdd: function onAdd(data) {
      var _this = this;

      if (this.isSubmit) {
        return;
      }

      this.isSubmit = true;
      this.$http.post('/api/accounts', data).then(function (res) {
        // show info
        _event_bus_js__WEBPACK_IMPORTED_MODULE_1__["EventBus"].$emit('show-success', 'Successfully saved account.'); // console.log('res is : ', res)

        _this.$router.replace({
          name: 'account list'
        });

        _this.isSubmit = false;
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_1__["EventBus"].$emit('show-error', err);
        _this.isSubmit = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
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



/* harmony default export */ __webpack_exports__["default"] = ({
  mixins: [vuelidate__WEBPACK_IMPORTED_MODULE_0__["validationMixin"]],
  validations: {
    form: {
      client_name: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        maxLength: Object(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["maxLength"])(30)
      },
      user_id: {
        required: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["required"],
        numeric: vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_1__["numeric"]
      }
    }
  },
  props: {
    submitText: {
      type: String,
      "default": 'Add'
    },
    submit: {
      type: Function,
      "default": function _default() {}
    },
    clientName: {
      type: String,
      "default": ''
    },
    clientNotes: {
      type: String,
      "default": ''
    },
    userId: {
      type: Number,
      "default": 0
    }
  },
  data: function data() {
    return {
      valid: false,
      users: [],
      form: {
        client_name: this.clientName,
        client_notes: this.clientNotes,
        user_id: this.userId
      }
    };
  },
  created: function created() {
    var _this = this;

    // we need to fetch all users 
    this.$http.get('/api/users').then(function (_ref) {
      var data = _ref.data;
      console.log('users : ', data);
      _this.users = data.data.map(function (user) {
        return {
          value: parseInt(user.id),
          text: user.attributes.name
        };
      });
    })["catch"](function (err) {
      _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', 'Failed to fetch users.');
    });
  },
  computed: {
    clientNameErrors: function clientNameErrors() {
      var errors = [];
      if (!this.$v.form.client_name.$dirty) return errors;
      !this.$v.form.client_name.maxLength && errors.push('Client name must be at most 30 characters long');
      !this.$v.form.client_name.required && errors.push('Client name is required.');
      return errors;
    },
    userIdErrors: function userIdErrors() {
      var errors = [];
      if (!this.$v.form.user_id.$dirty) return errors;
      !this.$v.form.user_id.required && errors.push('Bind to user is required.');
      return errors;
    }
  },
  methods: {
    onSubmit: function onSubmit(e) {
      this.$v.form.$touch();
      e.preventDefault();

      if (this.valid) {
        // call callback submit
        this.submit(this.form);
      } else {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', 'Failed! Please try again.');
      }
    },
    clear: function clear() {
      this.$refs.form.reset();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
    "v-row",
    [
      _c(
        "v-col",
        { attrs: { md: "6" } },
        [
          _c(
            "v-card",
            { attrs: { color: "light", padding: "" } },
            [
              _c("v-card-title", { staticClass: "headline" }, [
                _vm._v("Add New Account")
              ]),
              _vm._v(" "),
              _c("account-form", { attrs: { submit: _vm.onAdd } })
            ],
            1
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
          "error-messages": _vm.clientNameErrors,
          counter: 30,
          label: "Client Name",
          required: ""
        },
        on: {
          input: function($event) {
            return _vm.$v.form.client_name.$touch()
          },
          blur: function($event) {
            return _vm.$v.form.client_name.$touch()
          }
        },
        model: {
          value: _vm.form.client_name,
          callback: function($$v) {
            _vm.$set(_vm.form, "client_name", $$v)
          },
          expression: "form.client_name"
        }
      }),
      _vm._v(" "),
      _c("v-textarea", {
        staticClass: "mt-5",
        attrs: { outlined: "", label: "Client Notes" },
        model: {
          value: _vm.form.client_notes,
          callback: function($$v) {
            _vm.$set(_vm.form, "client_notes", $$v)
          },
          expression: "form.client_notes"
        }
      }),
      _vm._v(" "),
      _c("v-select", {
        attrs: {
          items: _vm.users,
          "error-messages": _vm.userIdErrors,
          rules: [
            function(v) {
              return !!v || "User is required"
            }
          ],
          label: "Choose User",
          required: ""
        },
        model: {
          value: _vm.form.user_id,
          callback: function($$v) {
            _vm.$set(_vm.form, "user_id", $$v)
          },
          expression: "form.user_id"
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
              staticClass: "mr-1",
              attrs: { type: "submit", small: "", color: "success" }
            },
            [_vm._v(_vm._s(_vm.submitText))]
          ),
          _vm._v(" "),
          _c("v-btn", { attrs: { small: "" }, on: { click: _vm.clear } }, [
            _vm._v("clear")
          ])
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

/***/ "./resources/js/components/accounts/AccountAdd.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/accounts/AccountAdd.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AccountAdd.vue?vue&type=template&id=e33bfebc& */ "./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc&");
/* harmony import */ var _AccountAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AccountAdd.vue?vue&type=script&lang=js& */ "./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AccountAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/accounts/AccountAdd.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountAdd.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountAdd.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountAdd_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc& ***!
  \****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountAdd.vue?vue&type=template&id=e33bfebc& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountAdd.vue?vue&type=template&id=e33bfebc&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountAdd_vue_vue_type_template_id_e33bfebc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/accounts/AccountForm.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/accounts/AccountForm.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AccountForm.vue?vue&type=template&id=b33926da& */ "./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da&");
/* harmony import */ var _AccountForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AccountForm.vue?vue&type=script&lang=js& */ "./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AccountForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/accounts/AccountForm.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountForm.vue?vue&type=template&id=b33926da& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/accounts/AccountForm.vue?vue&type=template&id=b33926da&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountForm_vue_vue_type_template_id_b33926da___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);