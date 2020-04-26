(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Dashboard.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../event-bus.js */ "./resources/js/event-bus.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      cards: [],
      latestIntegrations: [],
      isAdmin: true
    };
  },
  mounted: function mounted() {
    this.isAdmin = this.$store.getters.isAdmin;
    this.init();
  },
  computed: {
    user: function user() {
      return this.$store.state.user;
    }
  },
  methods: {
    init: function init() {
      var _this = this;

      // if not admin we need to redirect to its integrations record sync lists
      if (!this.isAdmin) {
        this.$router.replace('/logs');
        return;
      }

      this.$http.get('/api/dashboard').then(function (_ref) {
        var data = _ref.data;
        // console.log('data : ', data)
        _this.cards = data.cards;
        _this.latestIntegrations = data.integrations;
      });
    },
    gotoIntegration: function gotoIntegration(id) {
      this.$router.push({
        name: 'integration edit',
        params: {
          id: id
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9& ***!
  \************************************************************************************************************************************************************************************************************/
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
      _vm._m(0),
      _vm._v(" "),
      _vm.cards
        ? _c(
            "v-container",
            [
              _c(
                "v-row",
                _vm._l(_vm.cards, function(card) {
                  return _c(
                    "v-col",
                    { key: card.id },
                    [
                      _c(
                        "v-card",
                        { attrs: { color: "#385F73", dark: "" } },
                        [
                          _c("v-card-title", { staticClass: "headline" }, [
                            _c("i", { staticClass: "mr-3", class: card.icon }),
                            _vm._v(
                              "  " +
                                _vm._s(card.title) +
                                "\n                    "
                            )
                          ]),
                          _vm._v(" "),
                          _c("v-card-subtitle", [_vm._v(_vm._s(card.desc))]),
                          _vm._v(" "),
                          _c(
                            "v-card-actions",
                            [
                              _c(
                                "v-btn",
                                { attrs: { to: card.link, text: "" } },
                                [_vm._v("Show Details")]
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
                }),
                1
              ),
              _vm._v(" "),
              _c("v-divider"),
              _vm._v(" "),
              _c(
                "v-row",
                { attrs: { dense: "" } },
                [
                  _c(
                    "v-col",
                    [
                      _c(
                        "v-btn",
                        {
                          attrs: {
                            small: "",
                            color: "primary",
                            to: { name: "integration add" }
                          }
                        },
                        [_vm._v("Add Integrations")]
                      ),
                      _vm._v(" "),
                      _c(
                        "v-btn",
                        {
                          attrs: {
                            small: "",
                            color: "primary",
                            to: { name: "account add" }
                          }
                        },
                        [_vm._v("Add Account")]
                      )
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-row",
                { staticClass: "mt-3", attrs: { dense: "" } },
                [
                  _c(
                    "v-col",
                    [
                      _c("h5", [_vm._v("Latest Integration")]),
                      _vm._v(" "),
                      _c("v-simple-table", {
                        attrs: { light: true },
                        scopedSlots: _vm._u(
                          [
                            {
                              key: "default",
                              fn: function() {
                                return [
                                  _c("thead", [
                                    _c("tr", [
                                      _c("th", { staticClass: "text-left" }, [
                                        _vm._v("Integration")
                                      ]),
                                      _vm._v(" "),
                                      _c("th", { staticClass: "text-left" }, [
                                        _vm._v("Frequency (mins)")
                                      ]),
                                      _vm._v(" "),
                                      _c("th", { staticClass: "text-left" }, [
                                        _vm._v("Last Run")
                                      ]),
                                      _vm._v(" "),
                                      _c("th", { staticClass: "text-left" }, [
                                        _vm._v("Status")
                                      ])
                                    ])
                                  ]),
                                  _vm._v(" "),
                                  _vm.latestIntegrations.length > 0
                                    ? _c(
                                        "tbody",
                                        _vm._l(_vm.latestIntegrations, function(
                                          item
                                        ) {
                                          return _c(
                                            "tr",
                                            {
                                              key: "item-" + item.id,
                                              staticClass: "clickable",
                                              on: {
                                                click: function($event) {
                                                  return _vm.gotoIntegration(
                                                    item.id
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c("td", [
                                                _vm._v(_vm._s(item.label))
                                              ]),
                                              _vm._v(" "),
                                              _c("td", [
                                                _vm._v(
                                                  _vm._s(item.frequency_mins)
                                                )
                                              ]),
                                              _vm._v(" "),
                                              _c("td", [
                                                _vm._v(
                                                  _vm._s(
                                                    item.last_run || "none"
                                                  )
                                                )
                                              ]),
                                              _vm._v(" "),
                                              _c(
                                                "td",
                                                [
                                                  _c(
                                                    "v-chip",
                                                    {
                                                      attrs: {
                                                        "x-small": "",
                                                        color: _vm.getColor(
                                                          item.integration_status
                                                        ),
                                                        dark: ""
                                                      }
                                                    },
                                                    [
                                                      _vm._v(
                                                        _vm._s(
                                                          item.integration_status
                                                        )
                                                      )
                                                    ]
                                                  )
                                                ],
                                                1
                                              )
                                            ]
                                          )
                                        }),
                                        0
                                      )
                                    : _c("tbody", [
                                        _c("tr", [
                                          _c(
                                            "td",
                                            {
                                              staticClass: "text-center",
                                              attrs: { colspan: "4" }
                                            },
                                            [
                                              _c("p", [
                                                _vm._v("No integration yet.")
                                              ])
                                            ]
                                          )
                                        ])
                                      ])
                                ]
                              },
                              proxy: true
                            }
                          ],
                          null,
                          false,
                          1151586329
                        )
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        : _c(
            "v-container",
            [
              _c(
                "v-row",
                [_c("v-col", [_c("p", [_vm._v("Please wait...")])])],
                1
              )
            ],
            1
          )
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("h5", { staticClass: "title" }, [
      _c("i", { staticClass: "fas fa-tachometer-alt" }),
      _vm._v(" Dashboard")
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Dashboard.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/Dashboard.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=template&id=040e2ab9& */ "./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&");
/* harmony import */ var _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=script&lang=js& */ "./resources/js/components/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Dashboard.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Dashboard.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/components/Dashboard.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=template&id=040e2ab9& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);