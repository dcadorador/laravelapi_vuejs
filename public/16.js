(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[16],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsForm_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsForm.vue */ "./resources/js/components/integrations/IntegrationsForm.vue");
/* harmony import */ var _IntegrationsMapping_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsMapping.vue */ "./resources/js/components/integrations/IntegrationsMapping.vue");
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



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    IntegrationsForm: _IntegrationsForm_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    IntegrationsMapping: _IntegrationsMapping_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      id: null,
      integration: null,
      isSubmit: false,
      tab: 'tab-machship-keys',
      isActivate: null
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.id = parseInt(this.$route.params.id);
    this.$http.get('/api/integrations/' + this.id).then(function (_ref) {
      var data = _ref.data;
      _this.integration = data.data.attributes;
      _this.isActivate = _this.integration.integration_status == 'active' ? true : false;
    })["catch"](function (err) {
      _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', err);
    });
  },
  watch: {
    isActivate: function isActivate(value, oldValue) {
      if (oldValue != null) {
        this.onSetActivation(value);
        console.log('is activate : ', value);
      }
    }
  },
  methods: {
    onUpdate: function onUpdate(data) {
      var _this2 = this;

      if (this.isSubmit) {
        return;
      }

      this.isSubmit = true;
      this.$http.put('/api/integrations/' + this.id, data).then(function (res) {
        // show info
        _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-success', 'Successfully saved integrations.');
        _this2.isSubmit = false;
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', err);
        _this2.isSubmit = false;
      });
    },
    onSetActivation: function onSetActivation(value) {
      var status = value ? 'active' : 'inactive';
      this.$http.post('/api/integrations/activate', {
        integration_id: this.id,
        integration_status: status
      }).then(function () {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-success', 'Successfully sets integration status to ' + status);
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_2__["EventBus"].$emit('show-error', err);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
    "v-container",
    { attrs: { fluid: "" } },
    [
      _c(
        "v-row",
        { attrs: { dense: "" } },
        [
          _c(
            "v-col",
            [
              _c(
                "v-card",
                { attrs: { color: "light", padding: "", elevation: "6" } },
                [
                  _c(
                    "v-card-title",
                    { staticClass: "headline" },
                    [
                      _vm._v(
                        "\n                    Edit Integration\n                    "
                      ),
                      _c("v-spacer"),
                      _vm._v(" "),
                      _c("v-switch", {
                        attrs: { color: "primary" },
                        model: {
                          value: _vm.isActivate,
                          callback: function($$v) {
                            _vm.isActivate = $$v
                          },
                          expression: "isActivate"
                        }
                      }),
                      _c("span", { staticClass: "subtitle-2" }, [
                        _vm._v("Activate")
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.integration
                    ? _c("integrations-form", {
                        attrs: {
                          integration: _vm.integration,
                          integration_id: _vm.id,
                          integration_type_id:
                            _vm.integration.integration_type_id,
                          submit: _vm.onUpdate,
                          submitText: "Update"
                        }
                      })
                    : _c("v-progress-linear", {
                        staticClass: "mb-5",
                        attrs: { indeterminate: "", color: "primary" }
                      }),
                  _vm._v(" "),
                  _vm.integration
                    ? _c(
                        "div",
                        { staticClass: "p-3" },
                        [_c("integrations-mapping")],
                        1
                      )
                    : _vm._e()
                ],
                1
              )
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

/***/ "./resources/js/components/integrations/IntegrationsEdit.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsEdit.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsEdit.vue?vue&type=template&id=6c75101e& */ "./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e&");
/* harmony import */ var _IntegrationsEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsEdit.vue?vue&type=script&lang=js& */ "./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IntegrationsEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/integrations/IntegrationsEdit.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsEdit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsEdit.vue?vue&type=template&id=6c75101e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsEdit.vue?vue&type=template&id=6c75101e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsEdit_vue_vue_type_template_id_6c75101e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);