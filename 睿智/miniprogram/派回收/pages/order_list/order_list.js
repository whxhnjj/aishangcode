
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    page: 1,
    isReachBottom: 1,
    hasMoreData: [],
    order:[],
    order_text: '暂无更多订单'
  },

  onLoad: function (e) {
    let that =this;
    that.setData({
      status: e.status
    })
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      hasMoreData: [],
      order_text: '正在加载'
    })
    that.getOrderList(that.data.status, that.data.page, that.data.hasMoreData);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        hasMoreData: that.data.order
      })
      that.getOrderList(that.data.status, that.data.page, that.data.hasMoreData);
    }
  },

  

  onShow: function () {
    let that = this;
    that.setData({ page: 1, hasMoreData: [] })
    that.getOrderList(that.data.status, that.data.page, that.data.hasMoreData);
  },

  //切换订单
  changeOrderList:function(e){
    let that = this;
    that.setData({
      status: e.target.dataset.status,
      page:1,
      hasMoreData:[],
      order_text: '暂无更多订单'
    })
    that.getOrderList(that.data.status, that.data.page, that.data.hasMoreData);
  },

  //请求订单列表
  getOrderList: function (status, page, hasMoreData) {
    let that = this;
    core.request({
      url: 'api/customer/order_list',
      method: 'GET',
      data:{
        state: status,
        page: page,
        limit: 5
      },
      success: function (res) {

        if (page == 1) {
          that.setData({
            order: res.data.order,
            isReachBottom: 1,
          })
        }

        if (res.data.order.length <5) {
          that.setData({
            isReachBottom: 0,
            order_text: '暂无更多订单'
          });
        } else {

          var n = hasMoreData;
          var order = res.data.order;
          for (var i = 0; i < order.length; i++) {
            n.push(order[i])
          }

          that.setData({
            order: n,
          });

          page++;

          that.setData({
            page: page,
            order_text: '加载更多'
          })
        }

      },
      fail: function (res) {
      }
    })
  },

  //formid
  formidSubmit: function (e) {
    let that = this;
    console.log(e.detail.formId)
    if (e.detail.formId && e.detail.formId != 'the formId is a mock one') {

      core.request({
        url: 'api/complete/formid',
        method: 'POST',
        data: {
          formid: e.detail.formId
        },
        success: function (res) {
        },
        fail: function (res) {
        }
      });
    }
  }
  
})