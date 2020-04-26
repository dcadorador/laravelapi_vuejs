(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
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
  data: function data() {
    return {
      record_id: '',
      record: null,
      is_repulling: false,
      is_repushing: false,
      is_reprocessing: false
    };
  },
  computed: {
    debugLogs: function debugLogs() {
      return this.record.debug_logs ? this.record.debug_logs.sort(function (a, b) {
        return new Date(b.created_at) - new Date(a.created_at);
      }).map(function (log) {
        return {
          step: log.sync_step,
          created: log.created_at,
          data: log.data
        };
      }) : 'no debug logs.';
    },
    syncLogs: function syncLogs() {
      return this.record.sync_logs ? this.record.sync_logs.sort(function (a, b) {
        return new Date(b.created_at) - new Date(a.created_at);
      }).map(function (log) {
        return {
          step: log.step,
          created: log.created_at,
          data: log.data,
          result: log.result
        };
      }) : 'no sync logs.';
    },
    hasReActionOptions: function hasReActionOptions() {
      return this.$store.getters.isAdmin;
    }
  },
  mounted: function mounted() {
    var _this = this;

    this.record_id = parseInt(this.$route.params.id);
    this.$http.get('/api/integrationrecords/' + this.record_id).then(function (_ref) {
      var data = _ref.data;
      console.log('data : ', data);
      _this.record = data.data.attributes;
    });
  },
  methods: {
    onReAction: function onReAction(mode) {
      var _this2 = this;

      // we need to control re-actions
      if (this.is_repulling || this.is_repushing || this.is_reprocessing) {
        return;
      }

      var checker = false;

      switch (mode) {
        case 'repull':
          checker = 'is_repulling';
          break;

        case 'repush':
          checker = 'is_repushing';
          break;

        case 'reprocess':
          checker = 'is_reprocessing';
          break;
      }

      this[checker] = true;
      this.$http.post('/api/integrationrecords/' + mode, {
        id: this.record_id
      }).then(function (_ref2) {
        var data = _ref2.data;

        if (data.status) {
          _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-success', 'Record has been ' + mode + ' successfully');
          setTimeout(function () {
            _this2.$router.go();
          }, 1000);
        } else {
          _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', data.message);
          _this2[checker] = false;
        }
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', err);
        _this2[checker] = false;
      });
    },
    // record view execute re-pull record
    onRepull: function onRepull() {
      this.onReAction('repull');
    },
    // record view execute re-process record
    onReprocess: function onReprocess() {
      this.onReAction('reprocess');
    },
    // record view execute re-push record
    onRepush: function onRepush() {
      this.onReAction('repush');
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca& ***!
  \******************************************************************************************************************************************************************************************************************/
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
    { staticClass: "view-record" },
    [
      _c(
        "v-card",
        [
          _c("v-card-title", [
            _vm._v(
              "\n            View record # " +
                _vm._s(_vm.record_id) +
                "\n        "
            )
          ]),
          _vm._v(" "),
          _vm.record
            ? _c(
                "div",
                [
                  _c(
                    "v-container",
                    { attrs: { fluid: "" } },
                    [
                      _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            [
                              _c("h4", [
                                _vm._v(_vm._s(_vm.record.integration.label))
                              ]),
                              _vm._v(" "),
                              _c(
                                "div",
                                { staticClass: "d-flex" },
                                [
                                  _c(
                                    "p",
                                    [
                                      _vm._v(
                                        "\n                                Machship Consignment Status: \n                                "
                                      ),
                                      _vm.record.machship_consignment_status
                                        ? _c(
                                            "v-chip",
                                            {
                                              attrs: {
                                                color: _vm.getColor(
                                                  _vm.record
                                                    .machship_consignment_status
                                                ),
                                                dark: ""
                                              }
                                            },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(
                                                    _vm.record
                                                      .machship_consignment_status
                                                  ) +
                                                  "\n                                "
                                              )
                                            ]
                                          )
                                        : _vm._e()
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c("v-spacer"),
                                  _vm._v(" "),
                                  _c(
                                    "p",
                                    [
                                      _vm._v(
                                        "\n                                Record Status : \n                                "
                                      ),
                                      _vm.record.record_status
                                        ? _c(
                                            "v-chip",
                                            {
                                              attrs: {
                                                color: _vm.getColor(
                                                  _vm.record.record_status
                                                ),
                                                dark: ""
                                              }
                                            },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(
                                                    _vm.record.record_status
                                                  ) +
                                                  "\n                                "
                                              )
                                            ]
                                          )
                                        : _vm._e()
                                    ],
                                    1
                                  )
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _vm.hasReActionOptions
                                ? _c(
                                    "div",
                                    [
                                      _c(
                                        "v-btn",
                                        {
                                          attrs: {
                                            "x-small": "",
                                            color: "error",
                                            disabled: _vm.is_repulling
                                          },
                                          on: { click: _vm.onRepull }
                                        },
                                        [
                                          _vm.is_repulling
                                            ? _c(
                                                "span",
                                                [
                                                  _c("v-progress-circular", {
                                                    attrs: {
                                                      indeterminate: "",
                                                      size: "12",
                                                      width: "2",
                                                      color: "error darken-3"
                                                    }
                                                  }),
                                                  _vm._v(
                                                    "\n                                    Re-Pulling...\n                                "
                                                  )
                                                ],
                                                1
                                              )
                                            : _c("span", [
                                                _vm._v(
                                                  "\n                                    Re-Pull\n                                "
                                                )
                                              ])
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-btn",
                                        {
                                          attrs: {
                                            "x-small": "",
                                            color: "error",
                                            disabled: _vm.is_reprocessing
                                          },
                                          on: { click: _vm.onReprocess }
                                        },
                                        [
                                          _vm.is_reprocessing
                                            ? _c(
                                                "span",
                                                [
                                                  _c("v-progress-circular", {
                                                    attrs: {
                                                      indeterminate: "",
                                                      size: "12",
                                                      width: "2",
                                                      color: "error darken-3"
                                                    }
                                                  }),
                                                  _vm._v(
                                                    "\n                                    Re-Processing...\n                                "
                                                  )
                                                ],
                                                1
                                              )
                                            : _c("span", [
                                                _vm._v(
                                                  "\n                                    Re-Process\n                                "
                                                )
                                              ])
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-btn",
                                        {
                                          attrs: {
                                            "x-small": "",
                                            color: "error",
                                            disabled: _vm.is_repushing
                                          },
                                          on: { click: _vm.onRepush }
                                        },
                                        [
                                          _vm.is_repushing
                                            ? _c(
                                                "span",
                                                [
                                                  _c("v-progress-circular", {
                                                    attrs: {
                                                      indeterminate: "",
                                                      size: "12",
                                                      width: "2",
                                                      color: "error darken-3"
                                                    }
                                                  }),
                                                  _vm._v(
                                                    "\n                                    Re-Pushing...\n                                "
                                                  )
                                                ],
                                                1
                                              )
                                            : _c("span", [
                                                _vm._v(
                                                  "\n                                    Re-Push\n                                "
                                                )
                                              ])
                                        ]
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _c("v-divider", { staticClass: "mt-5" })
                            ],
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-row",
                        [
                          _c("v-col", { attrs: { md: "6" } }, [
                            _c("b", [_vm._v("Source data")]),
                            _vm._v(" "),
                            _c("pre", { staticClass: "record-data" }, [
                              _vm._v(_vm._s(_vm.record.source_data))
                            ])
                          ]),
                          _vm._v(" "),
                          _c("v-col", { attrs: { md: "6" } }, [
                            _c("b", [_vm._v("Machship Payload")]),
                            _vm._v(" "),
                            _c("pre", { staticClass: "record-data" }, [
                              _vm._v(_vm._s(_vm.record.machship_payload))
                            ])
                          ])
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-row",
                        [
                          _c("v-col", { attrs: { md: "6" } }, [
                            _c("b", [_vm._v("Debug Logs")]),
                            _vm._v(" "),
                            _c("pre", { staticClass: "record-data" }, [
                              _vm._v(_vm._s(_vm.debugLogs))
                            ])
                          ]),
                          _vm._v(" "),
                          _c("v-col", { attrs: { md: "6" } }, [
                            _c("b", [_vm._v("Sync Logs")]),
                            _vm._v(" "),
                            _c("pre", { staticClass: "record-data" }, [
                              _vm._v(_vm._s(_vm.syncLogs))
                            ])
                          ])
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              )
            : _c("v-progress-linear", {
                staticClass: "mb-5",
                attrs: { indeterminate: "", color: "primary" }
              })
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

/***/ "./resources/js/components/logs/RecordView.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/logs/RecordView.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RecordView.vue?vue&type=template&id=b397aeca& */ "./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca&");
/* harmony import */ var _RecordView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RecordView.vue?vue&type=script&lang=js& */ "./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RecordView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__["render"],
  _RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/logs/RecordView.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecordView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./RecordView.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/logs/RecordView.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RecordView_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./RecordView.vue?vue&type=template&id=b397aeca& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/logs/RecordView.vue?vue&type=template&id=b397aeca&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RecordView_vue_vue_type_template_id_b397aeca___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);