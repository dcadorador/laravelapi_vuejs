(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[4],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsMappingForm__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsMappingForm */ "./resources/js/components/integrations/IntegrationsMappingForm.vue");
/* harmony import */ var _IntegrationsMappingCarriers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsMappingCarriers */ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue");
/* harmony import */ var _IntegrationsValueLookupsForm__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./IntegrationsValueLookupsForm */ "./resources/js/components/integrations/IntegrationsValueLookupsForm.vue");
/* harmony import */ var _tabs_StatusMapping__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../tabs/StatusMapping */ "./resources/js/components/tabs/StatusMapping.vue");
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    IntegrationsMappingForm: _IntegrationsMappingForm__WEBPACK_IMPORTED_MODULE_0__["default"],
    IntegrationsMappingCarriers: _IntegrationsMappingCarriers__WEBPACK_IMPORTED_MODULE_1__["default"],
    IntegrationsValueLookupsForm: _IntegrationsValueLookupsForm__WEBPACK_IMPORTED_MODULE_2__["default"],
    StatusMapping: _tabs_StatusMapping__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  data: function data() {
    return {
      tab: 'tab-mapping',
      integrationId: null
    };
  },
  created: function created() {
    this.integrationId = parseInt(this.$route.params.id);
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _event_bus_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../event-bus.js */ "./resources/js/event-bus.js");
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    id: {
      type: Number,
      "default": null
    }
  },
  data: function data() {
    return {
      items: [],
      headers: [{
        text: 'Carrier',
        value: 'carrier'
      }, {
        text: 'Carrier Service',
        value: 'service'
      }, {
        text: 'Actions',
        value: 'actions'
      }],
      carrier: null,
      carriers: [],
      isSubmit: false,
      carrierService: null,
      carrierServices: []
    };
  },
  mounted: function mounted() {
    this.fetchMappingCarrierServices();
    this.fetchCarriers();
  },
  watch: {
    carrier: function carrier(value) {
      if (value) {
        console.log('on change carrier : ', value);
        this.fetchCarrierServices(value);
      }
    }
  },
  methods: {
    fetchMappingCarrierServices: function fetchMappingCarrierServices() {
      var _this = this;

      this.$http.get('/api/fieldmappers/carriers/' + this.id + '/services').then(function (_ref) {
        var data = _ref.data;

        if (data.status) {
          for (var _i = 0, _Object$entries = Object.entries(data.result); _i < _Object$entries.length; _i++) {
            var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
                key = _Object$entries$_i[0],
                value = _Object$entries$_i[1];

            _this.items.push({
              carrier: value.carrier,
              service: value.service
            });
          }
        }

        console.log('mapping carriers and services', data);
      });
    },
    fetchCarriers: function fetchCarriers() {
      var _this2 = this;

      this.$http.get('/api/fieldmappers/carriers/' + this.id).then(function (_ref2) {
        var data = _ref2.data;
        console.log('field mappers : ', data);
        _this2.carriers = data.map(function (icarrier) {
          return {
            value: icarrier,
            text: icarrier.name
          };
        });
      });
    },
    fetchCarrierServices: function fetchCarrierServices(carrier) {
      var _this3 = this;

      var params = {
        integration_id: this.id,
        carrier_id: carrier.id
      };
      this.$http.post('/api/fieldmappers/carrier/services', params).then(function (_ref3) {
        var data = _ref3.data;
        console.log('carrier service data : ', data);
        _this3.carrierServices = data.map(function (iservice) {
          return {
            value: iservice,
            text: iservice.name
          };
        });
      });
    },
    onDelete: function onDelete(map) {
      // remove current item selected
      var index = this.items.findIndex(function (item) {
        return item.id == map.id;
      });

      if (index >= 0) {
        this.items.splice(index, 1);
        this.$http["delete"]('/api/fieldmappers/carrier/' + this.id).then(function (_ref4) {
          var data = _ref4.data;
          console.log('carrier services mapping deleted');
        });
      }
    },
    onSubmit: function onSubmit(e) {
      var _this4 = this;

      e.preventDefault();
      var params = {
        integration_id: this.id,
        carrier_id: this.carrier.id,
        carrier_name: this.carrier.name,
        carrier_service_name: this.carrierService.name,
        carrier_service_id: this.carrierService.id
      };

      if (this.$refs.form.validate()) {
        this.isSubmit = true;
        this.$http.post('/api/fieldmappers/carrier', params).then(function (_ref5) {
          var data = _ref5.data;

          _this4.items.push({
            carrier: _this4.carrier.name,
            service: _this4.carrierService.name
          });

          _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-success', "Successfully saved carrier service mapping!");
          console.log('carrier service saved', data);
          _this4.carrier = null;
          _this4.carrierService = null;
        });
      } else {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', "Fail to save carrier service mapping! please try again.");
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    id: {
      type: Number,
      "default": null
    },
    direction: {
      type: String,
      "default": 'from_machship'
    }
  },
  data: function data() {
    return {
      valid: false,
      isSubmit: false,
      items: null,
      item_test: {
        data_direction: '',
        machship_field: '',
        map_type: '',
        source_field: '',
        data_conversion_type: '',
        integration_id: this.id
      },
      item_rows: [],
      dataDirections: [],
      dataConverionTypes: [],
      mapTypes: [],
      machshipFields: [],
      machshipItemFields: [],
      sourceFields: []
    };
  },
  mounted: function mounted() {
    this.fetchIntegration();
    this.fetchFieldMapperOptions();
  },
  methods: {
    onAddItemRow: function onAddItemRow() {
      if (this.item_rows.length > 0) {
        var last_row = this.item_rows[this.item_rows.length - 1];
        this.item_rows.push(parseInt(last_row) + 1);
        return;
      }

      this.item_rows = [0];
    },
    onRemoveItemRow: function onRemoveItemRow(row) {
      var _this = this;

      var items = this.items.filter(function (item) {
        return item.machship_field.indexOf("items.[" + row) >= 0 && item.id;
      }).map(function (item) {
        return item.id;
      });
      var index = this.item_rows.indexOf(row);
      this.item_rows.splice(index, 1);

      if (items.length > 0) {
        this.$http.post('/api/fieldmappers/removes', items).then(function (res) {
          console.log('result is : ', res); // we need to delete original client items too

          _this.items = _this.items.filter(function (item) {
            return item.machship_field.indexOf("items.[" + row) < 0;
          });
        });
      }
    },
    onChangeItemMapType: function onChangeItemMapType(e, field) {
      console.log('items : ', field);
      var index = this.items.findIndex(function (item) {
        return item.machship_field == field;
      });
      this.items[index].map_type = e.value;
    },
    onChangeSourceFields: function onChangeSourceFields(e, field) {
      var index = this.items.findIndex(function (item) {
        return item.machship_field == field;
      });
      this.items[index].source_field = e;
    },
    onChangeDataConversionType: function onChangeDataConversionType(e, field) {
      var index = this.items.findIndex(function (item) {
        return item.machship_field == field;
      });
      this.items[index].data_conversion_type = e.value;
    },
    onChangeDataConversionValue: function onChangeDataConversionValue(e, field) {
      var index = this.items.findIndex(function (item) {
        return item.machship_field == field;
      });
      this.items[index].data_conversion_value = e;
    },
    findItemByField: function findItemByField(machship_field) {
      var index = this.items.findIndex(function (item) {
        return item.machship_field == machship_field;
      });

      if (index >= 0) {
        return this.items[index];
      } else {
        return this.onAddMapping(machship_field);
      }
    },
    getItemsByRow: function getItemsByRow(row) {
      var field_row = [];
      this.machshipItemFields.forEach(function (field) {
        var field_split = field.split(".");
        var field_item = field_split[0] + '.[' + row + '].' + field_split[1];
        field_row.push(field_item);
      });
      return field_row;
    },
    onSubmit: function onSubmit(e) {
      var _this2 = this;

      e.preventDefault();

      if (this.$refs.form.validate()) {
        this.isSubmit = true;
        this.$http.post('/api/fieldmappers/bulk', this.items).then(function (_ref) {
          var data = _ref.data;
          _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-success', 'Successfully saved integrations.');
          console.log('success bulk save: ', data);
          _this2.items = data;
          _this2.isSubmit = false;
        })["catch"](function (err) {
          _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', err);
          _this2.isSubmit = false;
        });
      } else {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', 'Fail! Please check all required fields');
      }
    },
    onAddMapping: function onAddMapping(field) {
      this.item_test.machship_field = field;
      this.item_test.data_direction = this.direction;
      var id = Math.random().toString(36).substring(7);
      var new_item = Object.assign({
        _id: id
      }, this.item_test);
      this.items.push(new_item);
      return new_item;
    },
    fetchIntegration: function fetchIntegration() {
      var _this3 = this;

      this.$http.get('/api/fieldmappers/' + this.direction + '/' + this.id).then(function (_ref2) {
        var data = _ref2.data;
        _this3.items = data;

        _this3.checkMoreItems();
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', err);
      });
    },
    fetchFieldMapperOptions: function fetchFieldMapperOptions() {
      var _this4 = this;

      this.$http.get('api/fieldmappers/options/' + this.id).then(function (_ref3) {
        var data = _ref3.data;

        // console.log('options are : ', data)
        for (var index in data.options.directions) {
          _this4.dataDirections.push({
            value: index,
            text: data.options.directions[index]
          });
        }

        for (var _index in data.options.conversion_types) {
          _this4.dataConverionTypes.push({
            value: _index,
            text: data.options.conversion_types[_index]
          });
        }

        for (var _index2 in data.options.map_types) {
          _this4.mapTypes.push({
            value: _index2,
            text: data.options.map_types[_index2]
          });
        }

        data.machship_fields.sort();

        var _loop = function _loop(_index3) {
          if (data.machship_fields[_index3].indexOf("items.") >= 0) {
            _this4.machshipItemFields.push(data.machship_fields[_index3]);
          } else {
            setTimeout(function () {
              this.machshipFields.push(data.machship_fields[_index3]);
            }.bind(_this4), _index3 * 30);
          }
        };

        for (var _index3 in data.machship_fields) {
          _loop(_index3);
        }

        for (var _index4 in data.source_fields) {
          _this4.sourceFields.push(data.source_fields[_index4]);
        }
      });
    },
    checkMoreItems: function checkMoreItems() {
      var _this5 = this;

      // TODO to improve on getting more items row
      this.items.forEach(function (item) {
        if (item.machship_field.indexOf("items.[") >= 0) {
          var split_items = item.machship_field.split("items.[");

          if (split_items.length > 1) {
            var split_number = split_items[1].split("].");

            if (!_this5.item_rows[split_number[0]]) {
              _this5.item_rows.push(split_number[0]);
            } else {
              console.log('this item rows : ', _this5.item_rows[split_number[0]]);
            }
          }
        }
      });
      console.log('so the item rows is : ', this.item_rows);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
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

/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    // integration id
    id: {
      type: Number,
      "default": null
    }
  },
  data: function data() {
    return {
      items: null,
      isSubmit: false,
      statusList: []
    };
  },
  created: function created() {
    this.fetchStatusList();
    this.fetchStatusMapping();
  },
  methods: {
    fetchStatusList: function fetchStatusList() {
      var _this = this;

      this.$http.get('api/status/mapping/options').then(function (_ref) {
        var data = _ref.data;
        console.log('status mapping options: ', data);
        _this.statusList = data.record_status.map(function (item) {
          return {
            text: item,
            value: item
          };
        });
      });
    },
    fetchStatusMapping: function fetchStatusMapping() {
      var _this2 = this;

      if (!this.id) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', 'Invalid integration id');
        return;
      }

      this.$http.get('api/status/mapping', {
        params: {
          integration_id: this.id,
          limit: -1
        }
      }).then(function (_ref2) {
        var data = _ref2.data;
        _this2.items = data.data.map(function (item) {
          var obj = item.attributes;
          obj.id = item.id;
          return obj;
        });
      });
    },
    onSubmit: function onSubmit(e) {
      var _this3 = this;

      e.preventDefault();
      this.isSubmit = true;
      this.$http.post('api/status/mapping/bulk', this.items).then(function (_ref3) {
        var data = _ref3.data;
        console.log('has data: ', data);
        _this3.isSubmit = false;
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-success', 'Successfully updated status mapper');
      })["catch"](function (err) {
        _event_bus_js__WEBPACK_IMPORTED_MODULE_0__["EventBus"].$emit('show-error', err);
        _this3.isSubmit = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c& ***!
  \***********************************************************************************************************************************************************************************************************************************/
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
    "v-tabs",
    {
      staticClass: "fusedship-tab",
      attrs: { "background-color": "white", color: "dark" },
      model: {
        value: _vm.tab,
        callback: function($$v) {
          _vm.tab = $$v
        },
        expression: "tab"
      }
    },
    [
      _c("v-tabs-slider"),
      _vm._v(" "),
      _c("v-tab", { attrs: { href: "#to-machship" } }, [
        _vm._v("\n        TO Machship\n    ")
      ]),
      _vm._v(" "),
      _c("v-tab", { attrs: { href: "#from-machship" } }, [
        _vm._v("\n        FROM Machship\n    ")
      ]),
      _vm._v(" "),
      _c("v-tab", { attrs: { href: "#value-lookups" } }, [
        _vm._v("\n        Value Lookups\n    ")
      ]),
      _vm._v(" "),
      _c("v-tab", { attrs: { href: "#status-mapper" } }, [
        _vm._v("\n        Status Mapper\n    ")
      ]),
      _vm._v(" "),
      _c(
        "v-tab-item",
        { attrs: { value: "to-machship" } },
        [
          _c("IntegrationsMappingForm", {
            attrs: { id: _vm.integrationId, direction: "to_machship" }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-tab-item",
        { attrs: { value: "from-machship" } },
        [
          _c("IntegrationsMappingForm", {
            attrs: { id: _vm.integrationId, direction: "from_machship" }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-tab-item",
        { attrs: { value: "value-lookups" } },
        [
          _c("IntegrationsValueLookupsForm", {
            attrs: { id: _vm.integrationId, direction: "to_machship" }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-tab-item",
        { attrs: { value: "status-mapper" } },
        [_c("StatusMapping", { attrs: { id: _vm.integrationId } })],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
    "v-card",
    { staticClass: "mt-3", attrs: { color: "light" } },
    [
      _c(
        "v-card-title",
        { staticClass: "subtitle-2" },
        [
          _vm._v("\n        Mapping Carrier and Services "),
          _c("v-spacer"),
          _c("i", { staticClass: "fas fa-truck" })
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-form",
        { ref: "form", staticClass: "p-3 pt-0", on: { submit: _vm.onSubmit } },
        [
          _c(
            "v-container",
            [
              _c(
                "v-row",
                { attrs: { dense: "" } },
                [
                  _c(
                    "v-col",
                    { attrs: { lg: "4" } },
                    [
                      _c("v-autocomplete", {
                        attrs: {
                          items: _vm.carriers,
                          "menu-props": "auto",
                          label: "Carrier",
                          rules: [
                            function(v) {
                              return !!v || "Carriers is required"
                            }
                          ]
                        },
                        model: {
                          value: _vm.carrier,
                          callback: function($$v) {
                            _vm.carrier = $$v
                          },
                          expression: "carrier"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.carrier
                    ? _c(
                        "v-col",
                        { attrs: { lg: "6" } },
                        [
                          _c("v-autocomplete", {
                            attrs: {
                              items: _vm.carrierServices,
                              "menu-props": "auto",
                              label: "Carrier Services",
                              rules: [
                                function(v) {
                                  return !!v || "Carrier Services is required"
                                }
                              ]
                            },
                            model: {
                              value: _vm.carrierService,
                              callback: function($$v) {
                                _vm.carrierService = $$v
                              },
                              expression: "carrierService"
                            }
                          })
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.carrierService
                    ? _c(
                        "v-col",
                        { staticClass: "text-right" },
                        [
                          _c(
                            "v-btn",
                            {
                              staticClass: "mt-3",
                              attrs: { type: "submit", color: "success" }
                            },
                            [_vm._v("Save")]
                          )
                        ],
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
      ),
      _vm._v(" "),
      _vm.items
        ? _c("v-data-table", {
            attrs: {
              items: _vm.items,
              headers: _vm.headers,
              "disable-sort": true
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
                }
              ],
              null,
              false,
              3505994999
            )
          })
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e&":
/*!***************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e& ***!
  \***************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "mt-3 integration-mapping" },
    [
      _vm.items && _vm.machshipFields.length > 0
        ? _c(
            "v-form",
            {
              ref: "form",
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
              _c(
                "v-container",
                { attrs: { fluid: "" } },
                [
                  _c(
                    "v-row",
                    [
                      _c("v-col", [
                        _c("table", { staticClass: "table table-mapping" }, [
                          _c("thead", [
                            _c("tr", [
                              _c("th", [
                                _c("h5", { staticClass: "subtitle-2" }, [
                                  _vm._v("Machship Field")
                                ])
                              ]),
                              _vm._v(" "),
                              _c(
                                "th",
                                {
                                  staticClass: "bg-orange-panel",
                                  attrs: { colspan: "2" }
                                },
                                [
                                  _c("h5", { staticClass: "subtitle-2" }, [
                                    _vm._v("Field Mapper")
                                  ])
                                ]
                              ),
                              _vm._v(" "),
                              _c("th", { attrs: { width: "10" } }),
                              _vm._v(" "),
                              _c(
                                "th",
                                {
                                  staticClass: "bg-orange-panel",
                                  attrs: { colspan: "2" }
                                },
                                [
                                  _c("h5", { staticClass: "subtitle-2" }, [
                                    _vm._v("Data Conversion")
                                  ])
                                ]
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _c(
                            "tbody",
                            [
                              _c("tr", [
                                _c("td"),
                                _vm._v(" "),
                                _c(
                                  "td",
                                  { staticClass: "bg-orange-panel pb-0" },
                                  [_vm._v("Map Type")]
                                ),
                                _vm._v(" "),
                                _c(
                                  "td",
                                  { staticClass: "bg-orange-panel pb-0" },
                                  [_vm._v("Source Field")]
                                ),
                                _vm._v(" "),
                                _c("td"),
                                _vm._v(" "),
                                _c(
                                  "td",
                                  { staticClass: "bg-orange-panel pb-0" },
                                  [_vm._v("Conversion Type")]
                                ),
                                _vm._v(" "),
                                _c(
                                  "td",
                                  { staticClass: "bg-orange-panel pb-0" },
                                  [_vm._v("Conversion Value")]
                                )
                              ]),
                              _vm._v(" "),
                              _vm._l(_vm.machshipFields, function(
                                field,
                                index
                              ) {
                                return _c(
                                  "tr",
                                  {
                                    key: "field-" + index,
                                    staticClass: "p-3",
                                    attrs: {
                                      set: (_vm.item = _vm.findItemByField(
                                        field
                                      ))
                                    }
                                  },
                                  [
                                    _c("td", { staticClass: "pt-5" }, [
                                      _vm._v(_vm._s(field))
                                    ]),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass: "bg-orange-panel",
                                        attrs: { width: "120" }
                                      },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            items: _vm.mapTypes,
                                            value: _vm.item.map_type
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeItemMapType(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      { staticClass: "bg-orange-panel" },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            value: _vm.item.source_field,
                                            items: _vm.sourceFields
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeSourceFields(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c("td"),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass: "bg-orange-panel",
                                        attrs: { width: "120" }
                                      },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            items: _vm.dataConverionTypes,
                                            "menu-props": "auto",
                                            value: _vm.item.data_conversion_type
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeDataConversionType(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      { staticClass: "bg-orange-panel" },
                                      [
                                        _c("v-text-field", {
                                          attrs: {
                                            dense: "",
                                            value:
                                              _vm.item.data_conversion_value
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeDataConversionValue(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    )
                                  ]
                                )
                              }),
                              _vm._v(" "),
                              _c(
                                "tr",
                                { staticClass: "item-row-header mb-3" },
                                [
                                  _c("td", { staticClass: "pt-5" }, [
                                    _c(
                                      "h5",
                                      { staticClass: "mt-5 subtitle-2" },
                                      [_vm._v("Item *")]
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _c("td", {
                                    staticClass: "bg-orange-panel",
                                    attrs: { colspan: "2" }
                                  }),
                                  _vm._v(" "),
                                  _c("td"),
                                  _vm._v(" "),
                                  _c("td", {
                                    staticClass: "text-right bg-orange-panel",
                                    attrs: { colspan: "2" }
                                  })
                                ]
                              ),
                              _vm._v(" "),
                              _vm._l(_vm.machshipItemFields, function(
                                field,
                                index
                              ) {
                                return _c(
                                  "tr",
                                  {
                                    key: "itemfield-" + index,
                                    staticClass: "p-1",
                                    attrs: {
                                      set: (_vm.item = _vm.findItemByField(
                                        field
                                      ))
                                    }
                                  },
                                  [
                                    _c("td", { staticClass: "pt-5" }, [
                                      _vm._v(_vm._s(field))
                                    ]),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass: "bg-orange-panel",
                                        attrs: { width: "120" }
                                      },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            items: _vm.mapTypes,
                                            value: _vm.item.map_type
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeItemMapType(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      { staticClass: "bg-orange-panel" },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            value: _vm.item.source_field,
                                            items: _vm.sourceFields
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeSourceFields(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c("td"),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass: "bg-orange-panel",
                                        attrs: { width: "120" }
                                      },
                                      [
                                        _c("v-combobox", {
                                          attrs: {
                                            dense: "",
                                            items: _vm.dataConverionTypes,
                                            "menu-props": "auto",
                                            value: _vm.item.data_conversion_type
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeDataConversionType(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      { staticClass: "bg-orange-panel" },
                                      [
                                        _c("v-text-field", {
                                          attrs: {
                                            dense: "",
                                            value:
                                              _vm.item.data_conversion_value
                                          },
                                          on: {
                                            change: function($event) {
                                              return _vm.onChangeDataConversionValue(
                                                $event,
                                                field
                                              )
                                            }
                                          }
                                        })
                                      ],
                                      1
                                    )
                                  ]
                                )
                              })
                            ],
                            2
                          ),
                          _vm._v(" "),
                          _c(
                            "tfoot",
                            [
                              _vm._l(_vm.item_rows, function(row) {
                                return [
                                  _c("tr", { staticClass: "item-row-header" }, [
                                    _c("td", { staticClass: "pt-5" }, [
                                      _c(
                                        "h5",
                                        { staticClass: "mt-5 subtitle-2" },
                                        [_vm._v("Item " + _vm._s(row))]
                                      )
                                    ]),
                                    _vm._v(" "),
                                    _c("td", {
                                      staticClass: "bg-orange-panel",
                                      attrs: { colspan: "2" }
                                    }),
                                    _vm._v(" "),
                                    _c("td"),
                                    _vm._v(" "),
                                    _c(
                                      "td",
                                      {
                                        staticClass:
                                          "text-right bg-orange-panel",
                                        attrs: { colspan: "2" }
                                      },
                                      [
                                        _c(
                                          "v-btn",
                                          {
                                            attrs: {
                                              fab: "",
                                              dark: "",
                                              color: "orange"
                                            },
                                            on: {
                                              click: function($event) {
                                                return _vm.onRemoveItemRow(row)
                                              }
                                            }
                                          },
                                          [
                                            _c(
                                              "v-icon",
                                              { attrs: { dark: "" } },
                                              [_vm._v("mdi-minus")]
                                            )
                                          ],
                                          1
                                        )
                                      ],
                                      1
                                    )
                                  ]),
                                  _vm._v(" "),
                                  _vm._l(_vm.getItemsByRow(row), function(
                                    field,
                                    index
                                  ) {
                                    return _c(
                                      "tr",
                                      {
                                        key: "itemfield-" + row + "-" + index,
                                        attrs: {
                                          set: (_vm.item = _vm.findItemByField(
                                            field
                                          ))
                                        }
                                      },
                                      [
                                        _c("td", { staticClass: "pt-5" }, [
                                          _vm._v(_vm._s(field))
                                        ]),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          {
                                            staticClass: "bg-orange-panel",
                                            attrs: { width: "120" }
                                          },
                                          [
                                            _c("v-select", {
                                              attrs: {
                                                dense: "",
                                                items: _vm.mapTypes,
                                                value: _vm.item.map_type
                                              },
                                              on: {
                                                change: function($event) {
                                                  return _vm.onChangeItemMapType(
                                                    $event,
                                                    field
                                                  )
                                                }
                                              }
                                            })
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "bg-orange-panel" },
                                          [
                                            _c("v-combobox", {
                                              attrs: {
                                                dense: "",
                                                value: _vm.item.source_field,
                                                items: _vm.sourceFields
                                              },
                                              on: {
                                                change: function($event) {
                                                  return _vm.onChangeSourceFields(
                                                    $event,
                                                    field
                                                  )
                                                }
                                              }
                                            })
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c("td"),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          {
                                            staticClass: "bg-orange-panel",
                                            attrs: { width: "120" }
                                          },
                                          [
                                            _c("v-select", {
                                              attrs: {
                                                dense: "",
                                                items: _vm.dataConverionTypes,
                                                "menu-props": "auto",
                                                value:
                                                  _vm.item.data_conversion_type
                                              },
                                              on: {
                                                change: function($event) {
                                                  return _vm.onChangeDataConversionType(
                                                    $event,
                                                    field
                                                  )
                                                }
                                              }
                                            })
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          { staticClass: "bg-orange-panel" },
                                          [
                                            _c("v-text-field", {
                                              attrs: {
                                                dense: "",
                                                value:
                                                  _vm.item.data_conversion_value
                                              },
                                              on: {
                                                change: function($event) {
                                                  return _vm.onChangeDataConversionValue(
                                                    $event,
                                                    field
                                                  )
                                                }
                                              }
                                            })
                                          ],
                                          1
                                        )
                                      ]
                                    )
                                  })
                                ]
                              })
                            ],
                            2
                          )
                        ])
                      ])
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "mt-3 p-3" },
                [
                  _c(
                    "v-btn",
                    {
                      staticClass: "mx-2",
                      attrs: { fab: "", dark: "", color: "orange" },
                      on: { click: _vm.onAddItemRow }
                    },
                    [
                      _c("v-icon", { attrs: { dark: "" } }, [
                        _vm._v("mdi-plus")
                      ])
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "mt-5 p-3 d-flex" },
                [
                  _c("v-spacer"),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      staticClass: "mr-1",
                      attrs: {
                        type: "submit",
                        small: "",
                        color: "primary",
                        disabled: _vm.isSubmit
                      }
                    },
                    [
                      _vm.isSubmit
                        ? _c(
                            "span",
                            [
                              _c("v-progress-circular", {
                                attrs: {
                                  indeterminate: "",
                                  size: "12",
                                  width: "2",
                                  color: "green darken-3"
                                }
                              }),
                              _vm._v(
                                "\n                    updating...\n                "
                              )
                            ],
                            1
                          )
                        : _c("span", [_vm._v("Update")])
                    ]
                  )
                ],
                1
              )
            ],
            1
          )
        : _c("v-progress-linear", {
            attrs: { indeterminate: "", color: "primary" }
          })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
    { staticClass: "mt-3 integration-mapping" },
    [
      _vm.items
        ? _c(
            "v-form",
            {
              ref: "form",
              staticClass: "p-3 pt-0",
              on: { submit: _vm.onSubmit }
            },
            [
              _c(
                "v-container",
                { attrs: { fluid: "" } },
                [
                  _c(
                    "v-row",
                    [
                      _c("v-col", [
                        _c(
                          "table",
                          {
                            staticClass:
                              "table table-hover table-striped table-fs"
                          },
                          [
                            _c("thead", [
                              _c("tr", [
                                _c("th", { attrs: { width: "200" } }, [
                                  _vm._v("Consignment Status")
                                ]),
                                _vm._v(" "),
                                _c("th", [_vm._v("Record Status")]),
                                _vm._v(" "),
                                _c("th", { attrs: { width: "200" } }, [
                                  _vm._v("Update Source")
                                ])
                              ])
                            ]),
                            _vm._v(" "),
                            _vm.items.length > 0
                              ? _c(
                                  "tbody",
                                  _vm._l(_vm.items, function(item, index) {
                                    return _c(
                                      "tr",
                                      { key: "status-mapping-" + index },
                                      [
                                        _c("td", [
                                          _vm._v(_vm._s(item.machship_status))
                                        ]),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          [
                                            _c("v-select", {
                                              attrs: {
                                                dense: "",
                                                outlined: "",
                                                "full-width": false,
                                                items: _vm.statusList
                                              },
                                              model: {
                                                value: item.record_status,
                                                callback: function($$v) {
                                                  _vm.$set(
                                                    item,
                                                    "record_status",
                                                    $$v
                                                  )
                                                },
                                                expression: "item.record_status"
                                              }
                                            })
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          [
                                            _c("v-checkbox", {
                                              staticClass: "mx-auto",
                                              model: {
                                                value: item.update_source,
                                                callback: function($$v) {
                                                  _vm.$set(
                                                    item,
                                                    "update_source",
                                                    $$v
                                                  )
                                                },
                                                expression: "item.update_source"
                                              }
                                            })
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
                                    _c("td", { attrs: { colspan: "3" } }, [
                                      _c("p", [
                                        _vm._v("No machship status mappings")
                                      ])
                                    ])
                                  ])
                                ])
                          ]
                        )
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.items.length > 0
                    ? _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            { staticClass: "text-right" },
                            [
                              _c(
                                "v-btn",
                                {
                                  staticClass: "mr-1",
                                  attrs: {
                                    type: "submit",
                                    small: "",
                                    color: "primary",
                                    disabled: _vm.isSubmit
                                  }
                                },
                                [
                                  _vm.isSubmit
                                    ? _c(
                                        "span",
                                        [
                                          _c("v-progress-circular", {
                                            attrs: {
                                              indeterminate: "",
                                              size: "12",
                                              width: "2",
                                              color: "green darken-3"
                                            }
                                          }),
                                          _vm._v(
                                            "\n                            Updating..\n                        "
                                          )
                                        ],
                                        1
                                      )
                                    : _c("span", [_vm._v("Update")])
                                ]
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    : _vm._e()
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
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMapping.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMapping.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsMapping.vue?vue&type=template&id=b422e32c& */ "./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c&");
/* harmony import */ var _IntegrationsMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsMapping.vue?vue&type=script&lang=js& */ "./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IntegrationsMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/integrations/IntegrationsMapping.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMapping.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c& ***!
  \*****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMapping.vue?vue&type=template&id=b422e32c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMapping.vue?vue&type=template&id=b422e32c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMapping_vue_vue_type_template_id_b422e32c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue":
/*!******************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingCarriers.vue ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6& */ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6&");
/* harmony import */ var _IntegrationsMappingCarriers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsMappingCarriers.vue?vue&type=script&lang=js& */ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IntegrationsMappingCarriers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/integrations/IntegrationsMappingCarriers.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingCarriers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMappingCarriers.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingCarriers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingCarriers.vue?vue&type=template&id=eb2f88f6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingCarriers_vue_vue_type_template_id_eb2f88f6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingForm.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingForm.vue ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e& */ "./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e&");
/* harmony import */ var _IntegrationsMappingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IntegrationsMappingForm.vue?vue&type=script&lang=js& */ "./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IntegrationsMappingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/integrations/IntegrationsMappingForm.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMappingForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e& ***!
  \*********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/integrations/IntegrationsMappingForm.vue?vue&type=template&id=1c06c44e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IntegrationsMappingForm_vue_vue_type_template_id_1c06c44e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/tabs/StatusMapping.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/tabs/StatusMapping.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./StatusMapping.vue?vue&type=template&id=d3a540d4& */ "./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4&");
/* harmony import */ var _StatusMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StatusMapping.vue?vue&type=script&lang=js& */ "./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _StatusMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/tabs/StatusMapping.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StatusMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./StatusMapping.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/tabs/StatusMapping.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StatusMapping_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./StatusMapping.vue?vue&type=template&id=d3a540d4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/tabs/StatusMapping.vue?vue&type=template&id=d3a540d4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StatusMapping_vue_vue_type_template_id_d3a540d4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);