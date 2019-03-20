
import { Core } from '../../extend/core.js';
var core = new Core();


Page({
 
  data: {
    hostName: core.baseUrl,
    page: 1,
    isReachBottom: false,
    order:[],
    page_text: '正在加载'
  },

 
  onLoad: function (e) {
    let that = this;
    that.setData({
      state: e.state
    })
  },


  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      order: [],
      isReachBottom: false
    })
    that.getOrderList(that.data.page, that.data.state);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getOrderList(that.data.page, that.data.state);
    }
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, order: [], isReachBottom: false });
    that.getOrderList(that.data.page, that.data.state);
  },

  //切换订单
  changeOrderList: function (e) {
    let that = this;
    that.setData({
      state: e.target.dataset.state,
      page: 1,
      order: [],
      isReachBottom: false,
      page_text: '暂无更多订单'
    })
    that.getOrderList(that.data.page, that.data.state);
  },

  //请求订单列表
  getOrderList: function (page, state) {
    let that = this;
    core.request({
      url: 'api/order/lists',
      method: 'GET',
      data: {
        state: state,
        page: page,
        limit: 6
      },
      success: function (res) {
        
        var order = that.data.order;
        if (res.data.order.length < 6) {
          for (let i = 0; i < res.data.order.length; i++) {
            var list = res.data.order[i];
            list.order_amount = (list.order_amount/100).toFixed(2);
            order.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多订单',
            order: order
          });
        } else {
          for (let i = 0; i < res.data.order.length; i++) {
            var list = res.data.order[i];
            list.order_amount = (list.order_amount / 100).toFixed(2);
            order.push(list);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            order: order
          });
        }

      },
      fail: function (res) {
      }
    });
  }

})