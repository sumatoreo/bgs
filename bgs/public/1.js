(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Events.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Events.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../app */ "./resources/js/app.js");
//
//
//
//
//
//
//
//
//
//
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
  name: "Events",
  data: function data() {
    return {
      events: [],
      spinner: ''
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.load();
    _app__WEBPACK_IMPORTED_MODULE_0__["eventBus"].$on('eventLoad', function () {
      _this.load();
    });
  },
  methods: {
    load: function load() {
      var _this2 = this;

      axios.get('/api/events').then(function (response) {
        if (response.data.status === 'success') {
          _this2.events = response.data.payload;
        }
      })["catch"](function (_ref) {
        var response = _ref.response;

        if (response.data.status === 'error') {
          response.data.stack.forEach(function (message) {
            console.warn(message);
          });
        }
      });
    },
    detach: function detach(event, participant) {
      var _this3 = this;

      this.spinner = participant.id;
      axios.put('/api/events/' + event.id, {
        'action': 'detach',
        'participant_id': participant.id
      }).then(function (response) {
        if (response.data.status === 'success') {
          _this3.spinner = '';

          _this3.load();
        }
      })["catch"](function (_ref2) {
        var response = _ref2.response;

        if (response.data.status === 'error') {
          response.data.stack.forEach(function (message) {
            console.warn(message);
            _this3.spinner = '';
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true& ***!
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
    "ul",
    { staticClass: "list-group" },
    _vm._l(_vm.events, function(event) {
      return _c("li", { key: event.id, staticClass: "list-group-item" }, [
        _vm._v("\n        " + _vm._s(event.name) + "\n        "),
        event.participants.length > 0
          ? _c(
              "ul",
              { staticClass: "list-group" },
              _vm._l(event.participants, function(participant) {
                return _c(
                  "li",
                  { key: participant.id, staticClass: "list-group-item" },
                  [
                    _vm._v(
                      "\n                " +
                        _vm._s(participant.first_name) +
                        ", " +
                        _vm._s(participant.last_name) +
                        ", " +
                        _vm._s(participant.email) +
                        "\n                "
                    ),
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-link btn-sm",
                        attrs: {
                          type: "button",
                          disabled: participant.id === _vm.spinner
                        },
                        on: {
                          click: function($event) {
                            return _vm.detach(event, participant)
                          }
                        }
                      },
                      [
                        participant.id === _vm.spinner
                          ? _c("span", {
                              staticClass: "spinner-border spinner-border-sm",
                              attrs: { role: "status", "aria-hidden": "true" }
                            })
                          : _vm._e(),
                        _vm._v("\n                    Убрать\n                ")
                      ]
                    )
                  ]
                )
              }),
              0
            )
          : _vm._e()
      ])
    }),
    0
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Events.vue":
/*!********************************************!*\
  !*** ./resources/js/components/Events.vue ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Events.vue?vue&type=template&id=93914a78&scoped=true& */ "./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true&");
/* harmony import */ var _Events_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Events.vue?vue&type=script&lang=js& */ "./resources/js/components/Events.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Events_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "93914a78",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Events.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Events.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./resources/js/components/Events.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Events_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Events.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Events.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Events_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Events.vue?vue&type=template&id=93914a78&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Events.vue?vue&type=template&id=93914a78&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Events_vue_vue_type_template_id_93914a78_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);