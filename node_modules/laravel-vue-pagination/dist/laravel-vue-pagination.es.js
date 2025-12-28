import { resolveComponent as b, openBlock as d, createBlock as y, withCtx as k, createElementBlock as g, mergeProps as u, normalizeClass as v, createElementVNode as s, toHandlers as h, renderSlot as _, createCommentVNode as m, Fragment as C, renderList as w, createTextVNode as E, toDisplayString as U } from "vue";
const B = {
  emits: ["pagination-change-page"],
  props: {
    data: {
      type: Object,
      default: () => {
      }
    },
    limit: {
      type: Number,
      default: 0
    },
    keepLength: {
      type: Boolean,
      default: !1
    }
  },
  computed: {
    isApiResource() {
      return !!this.data.meta;
    },
    currentPage() {
      var e;
      return this.isApiResource ? this.data.meta.current_page : (e = this.data.current_page) != null ? e : null;
    },
    firstPageUrl() {
      var e, n, t, i, l;
      return (l = (i = (n = this.data.first_page_url) != null ? n : (e = this.data.meta) == null ? void 0 : e.first_page_url) != null ? i : (t = this.data.links) == null ? void 0 : t.first) != null ? l : null;
    },
    from() {
      var e;
      return this.isApiResource ? this.data.meta.from : (e = this.data.from) != null ? e : null;
    },
    lastPage() {
      var e;
      return this.isApiResource ? this.data.meta.last_page : (e = this.data.last_page) != null ? e : null;
    },
    lastPageUrl() {
      var e, n, t, i, l;
      return (l = (i = (n = this.data.last_page_url) != null ? n : (e = this.data.meta) == null ? void 0 : e.last_page_url) != null ? i : (t = this.data.links) == null ? void 0 : t.last) != null ? l : null;
    },
    nextPageUrl() {
      var e, n, t, i, l;
      return (l = (i = (n = this.data.next_page_url) != null ? n : (e = this.data.meta) == null ? void 0 : e.next_page_url) != null ? i : (t = this.data.links) == null ? void 0 : t.next) != null ? l : null;
    },
    perPage() {
      var e;
      return this.isApiResource ? this.data.meta.per_page : (e = this.data.per_page) != null ? e : null;
    },
    prevPageUrl() {
      var e, n, t, i, l;
      return (l = (i = (n = this.data.prev_page_url) != null ? n : (e = this.data.meta) == null ? void 0 : e.prev_page_url) != null ? i : (t = this.data.links) == null ? void 0 : t.prev) != null ? l : null;
    },
    to() {
      var e;
      return this.isApiResource ? this.data.meta.to : (e = this.data.to) != null ? e : null;
    },
    total() {
      var e;
      return this.isApiResource ? this.data.meta.total : (e = this.data.total) != null ? e : null;
    },
    pageRange() {
      if (this.limit === -1)
        return 0;
      if (this.limit === 0)
        return this.lastPage;
      for (var e = this.currentPage, n = this.keepLength, t = this.lastPage, i = this.limit, l = e - i, f = e + i, p = (i + 2) * 2, a = (i + 2) * 2 - 1, r = [], c = [], P, o = 1; o <= t; o++)
        (o === 1 || o === t || o >= l && o <= f || n && o < p && e < p - 2 || n && o > t - a && e > t - a + 2) && r.push(o);
      return r.forEach(function(x) {
        P && (x - P === 2 ? c.push(P + 1) : x - P !== 1 && c.push("...")), c.push(x), P = x;
      }), c;
    }
  },
  methods: {
    previousPage() {
      this.selectPage(this.currentPage - 1);
    },
    nextPage() {
      this.selectPage(this.currentPage + 1);
    },
    selectPage(e) {
      e === "..." || e === this.currentPage || this.$emit("pagination-change-page", e);
    }
  },
  render() {
    return this.$slots.default({
      data: this.data,
      limit: this.limit,
      computed: {
        isApiResource: this.isApiResource,
        currentPage: this.currentPage,
        firstPageUrl: this.firstPageUrl,
        from: this.from,
        lastPage: this.lastPage,
        lastPageUrl: this.lastPageUrl,
        nextPageUrl: this.nextPageUrl,
        perPage: this.perPage,
        prevPageUrl: this.prevPageUrl,
        to: this.to,
        total: this.total,
        pageRange: this.pageRange
      },
      prevButtonEvents: {
        click: (e) => {
          e.preventDefault(), this.previousPage();
        }
      },
      nextButtonEvents: {
        click: (e) => {
          e.preventDefault(), this.nextPage();
        }
      },
      pageButtonEvents: (e) => ({
        click: (n) => {
          n.preventDefault(), this.selectPage(e);
        }
      })
    });
  }
}, R = (e, n) => {
  const t = e.__vccOpts || e;
  for (const [i, l] of n)
    t[i] = l;
  return t;
}, $ = {
  compatConfig: {
    MODE: 3
  },
  inheritAttrs: !1,
  emits: ["pagination-change-page"],
  components: {
    RenderlessPagination: B
  },
  props: {
    data: {
      type: Object,
      default: () => {
      }
    },
    limit: {
      type: Number,
      default: 0
    },
    keepLength: {
      type: Boolean,
      default: !1
    },
    showDisabled: {
      type: Boolean,
      default: !1
    },
    size: {
      type: String,
      default: "default",
      validator: (e) => ["small", "default", "large"].indexOf(e) !== -1
    },
    align: {
      type: String,
      default: "left",
      validator: (e) => ["left", "center", "right"].indexOf(e) !== -1
    }
  },
  methods: {
    onPaginationChangePage(e) {
      this.$emit("pagination-change-page", e);
    }
  }
}, A = ["tabindex"], D = /* @__PURE__ */ s("span", { "aria-hidden": "true" }, "\xAB", -1), z = /* @__PURE__ */ s("span", { class: "sr-only" }, "Previous", -1), O = {
  key: 0,
  class: "sr-only"
}, N = ["tabindex"], j = /* @__PURE__ */ s("span", { "aria-hidden": "true" }, "\xBB", -1), L = /* @__PURE__ */ s("span", { class: "sr-only" }, "Next", -1);
function S(e, n, t, i, l, f) {
  const p = b("RenderlessPagination");
  return d(), y(p, {
    data: t.data,
    limit: t.limit,
    "keep-length": t.keepLength,
    onPaginationChangePage: f.onPaginationChangePage
  }, {
    default: k((a) => [
      a.computed.total > a.computed.perPage ? (d(), g("ul", u({ key: 0 }, e.$attrs, {
        class: ["pagination", {
          "pagination-sm": t.size == "small",
          "pagination-lg": t.size == "large",
          "justify-content-center": t.align == "center",
          "justify-content-end": t.align == "right"
        }]
      }), [
        a.computed.prevPageUrl || t.showDisabled ? (d(), g("li", {
          key: 0,
          class: v(["page-item pagination-prev-nav", { disabled: !a.computed.prevPageUrl }])
        }, [
          s("a", u({
            class: "page-link",
            href: "#",
            "aria-label": "Previous",
            tabindex: !a.computed.prevPageUrl && -1
          }, h(a.prevButtonEvents, !0)), [
            _(e.$slots, "prev-nav", {}, () => [
              D,
              z
            ])
          ], 16, A)
        ], 2)) : m("", !0),
        (d(!0), g(C, null, w(a.computed.pageRange, (r, c) => (d(), g("li", {
          class: v(["page-item pagination-page-nav", { active: r == a.computed.currentPage }]),
          key: c
        }, [
          s("a", u({
            class: "page-link",
            href: "#"
          }, h(a.pageButtonEvents(r), !0)), [
            E(U(r) + " ", 1),
            r == a.computed.currentPage ? (d(), g("span", O, "(current)")) : m("", !0)
          ], 16)
        ], 2))), 128)),
        a.computed.nextPageUrl || t.showDisabled ? (d(), g("li", {
          key: 1,
          class: v(["page-item pagination-next-nav", { disabled: !a.computed.nextPageUrl }])
        }, [
          s("a", u({
            class: "page-link",
            href: "#",
            "aria-label": "Next",
            tabindex: !a.computed.nextPageUrl && -1
          }, h(a.nextButtonEvents, !0)), [
            _(e.$slots, "next-nav", {}, () => [
              j,
              L
            ])
          ], 16, N)
        ], 2)) : m("", !0)
      ], 16)) : m("", !0)
    ]),
    _: 3
  }, 8, ["data", "limit", "keep-length", "onPaginationChangePage"]);
}
const ae = /* @__PURE__ */ R($, [["render", S]]), M = {
  compatConfig: {
    MODE: 3
  },
  inheritAttrs: !1,
  emits: ["pagination-change-page"],
  components: {
    RenderlessPagination: B
  },
  props: {
    data: {
      type: Object,
      default: () => {
      }
    },
    limit: {
      type: Number,
      default: 0
    },
    showDisabled: {
      type: Boolean,
      default: !1
    },
    keepLength: {
      type: Boolean,
      default: !1
    },
    size: {
      type: String,
      default: "default",
      validator: (e) => ["small", "default", "large"].indexOf(e) !== -1
    },
    align: {
      type: String,
      default: "left",
      validator: (e) => ["left", "center", "right"].indexOf(e) !== -1
    }
  },
  methods: {
    onPaginationChangePage(e) {
      this.$emit("pagination-change-page", e);
    }
  }
}, V = ["tabindex"], T = /* @__PURE__ */ s("span", { "aria-hidden": "true" }, "\xAB", -1), F = ["aria-current"], H = ["tabindex"], q = /* @__PURE__ */ s("span", { "aria-hidden": "true" }, "\xBB", -1);
function G(e, n, t, i, l, f) {
  const p = b("RenderlessPagination");
  return d(), y(p, {
    data: t.data,
    limit: t.limit,
    "keep-length": t.keepLength,
    onPaginationChangePage: f.onPaginationChangePage
  }, {
    default: k((a) => [
      a.computed.total > a.computed.perPage ? (d(), g("ul", u({ key: 0 }, e.$attrs, {
        class: ["pagination", {
          "pagination-sm": t.size == "small",
          "pagination-lg": t.size == "large",
          "justify-content-center": t.align == "center",
          "justify-content-end": t.align == "right"
        }]
      }), [
        a.computed.prevPageUrl || t.showDisabled ? (d(), g("li", {
          key: 0,
          class: v(["page-item pagination-prev-nav", { disabled: !a.computed.prevPageUrl }])
        }, [
          s("a", u({
            class: "page-link",
            href: "#",
            "aria-label": "Previous",
            tabindex: !a.computed.prevPageUrl && -1
          }, h(a.prevButtonEvents, !0)), [
            _(e.$slots, "prev-nav", {}, () => [
              T
            ])
          ], 16, V)
        ], 2)) : m("", !0),
        (d(!0), g(C, null, w(a.computed.pageRange, (r, c) => (d(), g("li", {
          class: v(["page-item pagination-page-nav", { active: r == a.computed.currentPage }]),
          key: c
        }, [
          s("a", u({
            class: "page-link",
            href: "#"
          }, h(a.pageButtonEvents(r), !0), {
            "aria-current": r == a.computed.currentPage ? "page" : null
          }), U(r), 17, F)
        ], 2))), 128)),
        a.computed.nextPageUrl || t.showDisabled ? (d(), g("li", {
          key: 1,
          class: v(["page-item pagination-next-nav", { disabled: !a.computed.nextPageUrl }])
        }, [
          s("a", u({
            class: "page-link",
            href: "#",
            "aria-label": "Next",
            tabindex: !a.computed.nextPageUrl && -1
          }, h(a.nextButtonEvents, !0)), [
            _(e.$slots, "next-nav", {}, () => [
              q
            ])
          ], 16, H)
        ], 2)) : m("", !0)
      ], 16)) : m("", !0)
    ]),
    _: 3
  }, 8, ["data", "limit", "keep-length", "onPaginationChangePage"]);
}
const ne = /* @__PURE__ */ R(M, [["render", G]]), I = {
  compatConfig: {
    MODE: 3
  },
  inheritAttrs: !1,
  emits: ["pagination-change-page"],
  components: {
    RenderlessPagination: B
  },
  props: {
    data: {
      type: Object,
      default: () => {
      }
    },
    limit: {
      type: Number,
      default: 0
    },
    keepLength: {
      type: Boolean,
      default: !1
    },
    itemClasses: {
      type: Array,
      default: () => [
        "bg-white",
        "text-gray-500",
        "border-gray-300",
        "hover:bg-gray-50"
      ]
    },
    activeClasses: {
      type: Array,
      default: () => [
        "bg-blue-50",
        "border-blue-500",
        "text-blue-600"
      ]
    }
  },
  methods: {
    onPaginationChangePage(e) {
      this.$emit("pagination-change-page", e);
    }
  }
}, J = ["disabled"], K = /* @__PURE__ */ s("span", { class: "sr-only" }, "Previous", -1), Q = /* @__PURE__ */ s("svg", {
  class: "w-5 h-5",
  "aria-hidden": "true",
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor"
}, [
  /* @__PURE__ */ s("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M15.75 19.5L8.25 12l7.5-7.5"
  })
], -1), W = ["aria-current", "disabled"], X = ["disabled"], Y = /* @__PURE__ */ s("span", { class: "sr-only" }, "Next", -1), Z = /* @__PURE__ */ s("svg", {
  class: "w-5 h-5",
  "aria-hidden": "true",
  xmlns: "http://www.w3.org/2000/svg",
  fill: "none",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  stroke: "currentColor"
}, [
  /* @__PURE__ */ s("path", {
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    d: "M8.25 4.5l7.5 7.5-7.5 7.5"
  })
], -1);
function ee(e, n, t, i, l, f) {
  const p = b("RenderlessPagination");
  return d(), y(p, {
    data: t.data,
    limit: t.limit,
    "keep-length": t.keepLength,
    onPaginationChangePage: f.onPaginationChangePage
  }, {
    default: k((a) => [
      a.computed.total > a.computed.perPage ? (d(), g("nav", u({ key: 0 }, e.$attrs, {
        class: "inline-flex -space-x-px rounded-md shadow-sm isolate ltr:flex-row rtl:flex-row-reverse",
        "aria-label": "Pagination"
      }), [
        s("button", u({
          class: ["relative inline-flex items-center px-2 py-2 text-sm font-medium border rounded-l-md focus:z-20 disabled:opacity-50", t.itemClasses],
          disabled: !a.computed.prevPageUrl
        }, h(a.prevButtonEvents, !0)), [
          _(e.$slots, "prev-nav", {}, () => [
            K,
            Q
          ])
        ], 16, J),
        (d(!0), g(C, null, w(a.computed.pageRange, (r, c) => (d(), g("button", u({
          class: ["relative inline-flex items-center px-4 py-2 text-sm font-medium border focus:z-20", [
            r == a.computed.currentPage ? t.activeClasses : t.itemClasses,
            r == a.computed.currentPage ? "z-30" : ""
          ]],
          "aria-current": a.computed.currentPage ? "page" : null,
          key: c
        }, h(a.pageButtonEvents(r), !0), {
          disabled: r === a.computed.currentPage
        }), U(r), 17, W))), 128)),
        s("button", u({
          class: ["relative inline-flex items-center px-2 py-2 text-sm font-medium border rounded-r-md focus:z-20 disabled:opacity-50", t.itemClasses],
          disabled: !a.computed.nextPageUrl
        }, h(a.nextButtonEvents, !0)), [
          _(e.$slots, "next-nav", {}, () => [
            Y,
            Z
          ])
        ], 16, X)
      ], 16)) : m("", !0)
    ]),
    _: 3
  }, 8, ["data", "limit", "keep-length", "onPaginationChangePage"]);
}
const ie = /* @__PURE__ */ R(I, [["render", ee]]);
export {
  ae as Bootstrap4Pagination,
  ne as Bootstrap5Pagination,
  B as RenderlessPagination,
  ie as TailwindPagination
};
