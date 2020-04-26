(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[7],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modal.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modal.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    width: {
      type: String,
      "default": '290'
    },
    color: {
      type: String,
      "default": 'light'
    },
    okText: {
      type: String,
      "default": 'Ok'
    },
    cancelText: {
      type: String,
      "default": 'Cancel'
    },
    isOkayOnly: {
      type: Boolean,
      "default": false
    }
  },
  data: function data() {
    return {
      isShow: false,
      title: '',
      message: '',
      callback: function callback() {}
    };
  },
  methods: {
    // this function accepts title and message of the modal to show
    // cb is the callback function to execute after ok button clicked
    onShow: function onShow(title, message, cb) {
      this.isShow = true;
      this.title = title;
      this.message = message;
      this.callback = cb;
    },
    // on ok execute
    onOk: function onOk() {
      this.isShow = false;

      if (typeof this.callback == 'function') {
        this.callback();
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Modal */ "./resources/js/components/Modal.vue");
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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
  components: {
    Modal: _Modal__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      headers: [{
        text: 'Integration label',
        value: 'label'
      }, {
        text: 'Integration type',
        value: 'integration_type'
      }, {
        text: 'Account',
        value: 'account'
      }, {
        text: 'Frequency (mins)',
        value: 'frequency'
      }, {
        text: 'Status',
        value: 'status'
      }, {
        text: 'Actions',
        value: 'actions',
        width: 350
      }],
      items: [],
      options: this.getPaginateOptions(),
      total: 0,
      search: '',
      loading: true,
      integrationId: null
    };
  },
  created: function created() {
    this.fetchPage();
  },
  computed: {
    params: function params() {
      return _objectSpread({}, this.options, {
        query: this.search
      });
    }
  },
  watch: {
    params: function params(val, oldVal) {
      // this is to avoid redundant request
      if (JSON.stringify(val) != JSON.stringify(oldVal)) {
        this.fetchPage();
      }
    }
  },
  methods: {
    onDelete: function onDelete(item) {
      this.integrationId = item.id;
      this.$refs.modal.onShow('Delete Confirmation', 'Are you sure you want to delete this account?', this.onDeleteConfirm);
    },
    onDeleteConfirm: function onDeleteConfirm() {
      var _this = this;

      this.$http["delete"]('/api/integrations/' + this.integrationId).then(function (_ref) {
        var data = _ref.data;
        _event_bus_js__WEBPACK_IMPORTED_MODULE_1__["EventBus"].$emit('show-success', 'Successfully deleted an account.'); // remove current item selected

        var index = _this.items.findIndex(function (item) {
          return item.id == _this.integrationId;
        });

        if (index >= 0) {
          _this.items.splice(index, 1);
        }
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_1__["EventBus"].$emit('show-error', err);
      });
    },
    onSearch: function onSearch(text) {
      this.search = text;
      this.options.page = 1;
    },
    fetchPage: function fetchPage() {
      var _this2 = this;

      var _this$options = this.options,
          sortBy = _this$options.sortBy,
          sortDesc = _this$options.sortDesc,
          page = _this$options.page,
          itemsPerPage = _this$options.itemsPerPage;
      this.loading = true;
      this.$http.get('/api/integrations', {
        params: {
          page: page,
          limit: itemsPerPage,
          sortBy: sortBy ? sortBy[0] : null,
          sortDesc: sortDesc ? sortDesc[0] : null,
          search: this.search
        }
      }).then(function (_ref2) {
        var data = _ref2.data;
        // map data to items
        _this2.items = data.data.map(function (item) {
          return {
            id: item.id,
            label: item.attributes.label,
            integration_type: item.attributes.integration_type.name,
            account: item.attributes.account ? item.attributes.account.client_name : '',
            frequency: item.attributes.frequency_mins,
            last: item.attributes.last_run,
            status: item.attributes.integration_status
          };
        }); // pagination

        var pagination = data.meta.pagination;
        _this2.page = pagination.current_page;
        _this2.length = pagination.total_pages;
        _this2.size = pagination.per_page;
        _this2.total = pagination.total;
        _this2.loading = false;
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_1__["EventBus"].$emit('show-error', err);
        _this2.loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2& ***!
  \********************************************************************************************************************************************************************************************************/
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
    "v-dialog",
    {
      attrs: { persistent: "", "max-width": _vm.width },
      model: {
        value: _vm.isShow,
        callback: function($$v) {
          _vm.isShow = $$v
        },
        expression: "isShow"
      }
    },
    [
      _c(
        "v-card",
        [
          _c("v-card-title", { staticClass: "headline" }, [
            _vm._v(_vm._s(_vm.title))
          ]),
          _vm._v(" "),
          _c("v-card-text", [_vm._v(_vm._s(_vm.message))]),
          _vm._v(" "),
          _c(
            "v-card-actions",
            [
              _c("v-spacer"),
              _vm._v(" "),
              !_vm.isOkayOnly
                ? _c(
                    "v-btn",
                    {
                      attrs: { color: "default darken-1", text: "" },
                      on: {
                        click: function($event) {
                          _vm.isShow = false
                        }
                      }
                    },
                    [_vm._v(_vm._s(_vm.cancelText))]
                  )
                : _vm._e(),
              _vm._v(" "),
              _c(
                "v-btn",
                {
                  attrs: { color: _vm.color + " darken-1", text: "" },
                  on: { click: _vm.onOk }
                },
                [_vm._v(_vm._s(_vm.okText))]
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2& ***!
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
    "div",
    [
      _c(
        "v-card",
        [
          _c(
            "v-card-title",
            [
              _c("i", { staticClass: "fas fa-list mr-3" }),
              _vm._v(" Integration Lists\n            "),
              _c("v-spacer"),
              _vm._v(" "),
              _c("v-text-field", {
                staticStyle: { "margin-top": "-10px" },
                attrs: {
                  "append-icon": "search",
                  label: "Search",
                  "single-line": "",
                  "hide-details": ""
                },
                on: {
                  change: function($event) {
                    return _vm.onSearch($event)
                  }
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _vm.items
            ? _c("v-data-table", {
                attrs: {
                  headers: _vm.headers,
                  options: _vm.options,
                  "server-items-length": _vm.total,
                  items: _vm.items,
                  loading: _vm.loading,
                  "disable-sort": true
                },
                on: {
                  "update:options": function($event) {
                    _vm.options = $event
                  }
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "item.actions",
                      fn: function(ref) {
                        var item = ref.item
                        return [
                          _c(
                            "v-btn",
                            {
                              attrs: {
                                small: "",
                                color: "success",
                                to: "/integrations/" + item.id
                              }
                            },
                            [_vm._v("Edit")]
                          ),
                          _vm._v(" "),
                          _c(
                            "v-btn",
                            {
                              attrs: { small: "", color: "error" },
                              on: {
                                click: function($event) {
                                  return _vm.onDelete(item)
                                }
                              }
                            },
                            [_vm._v("Delete")]
                          )
                        ]
                      }
                    },
                    {
                      key: "item.status",
                      fn: function(ref) {
                        var item = ref.item
                        return [
                          _c(
                            "v-chip",
                            {
                              attrs: {
                                "x-small": "",
                                color: _vm.getColor(item.status),
                                dark: ""
                              }
                            },
                            [_vm._v(_vm._s(item.status))]
                          )
                        ]
                      }
                    }
                  ],
                  null,
                  false,
                  424684611
                )
              })
            : _c("v-progress-linear", {
                staticClass: "mb-5",
                attrs: { indeterminate: "", color: "primary" }
              })
        ],
        1
      ),
      _vm._v(" "),
      _c("Modal", { ref: "modal", attrs: { color: "error", okText: "delete" } })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Modal.vue":
/*!*******************************************!*\
  !*** ./resources/js/components/Modal.vue ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Modal.vue?vue&type=template&id=53ab54d2& */ "./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2&");
/* harmony import */ var _Modal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Modal.vue?vue&type=script&lang=js& */ "./resources/js/components/Modal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Modal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Modal.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Modal.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/components/Modal.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Modal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Modal.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Modal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2&":
/*!**************************************************************************!*\
  !*** ./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2& ***!
  \**************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Modal.vue?vue&type=template&id=53ab54d2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modal.vue?vue&type=template&id=53ab54d2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Modal_vue_vue_type_template_id_53ab54d2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsList.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsList.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsList.vue?vue&type=template&id=5d2ce8b2& */ "./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2&");
/* harmony import */ var _IntegrationsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsList.vue?vue&type=script&lang=js& */ "./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IntegrationsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/integrations/IntegrationsList.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsList.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsList.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsList_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsList.vue?vue&type=template&id=5d2ce8b2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsList.vue?vue&type=template&id=5d2ce8b2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsList_vue_vue_type_template_id_5d2ce8b2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);