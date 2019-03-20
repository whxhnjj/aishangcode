
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    hostName: core.baseUrl,
    goods: [],
    page: 1,
    isReachBottom: false,
    load_more: '加载更多'
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      load_more: '正在加载',
      goods: [],
      isReachBottom: false
    })
    that.requestSearch(that.data.name, that.data.page);
    wx.stopPullDownRefresh()
  },

  //监听用户上拉触底
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.requestSearch(that.data.name, that.data.page);
    }
  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      page: 1,
      goods: [],
      isReachBottom: false,
      name: e.name,
    })
    that.requestSearch(that.data.name, that.data.page, that.data.original);
  },


  //请求搜索商品
  requestSearch: function (name, page) {
    let that = this;
    core.request({
      url: 'api/goods/lists',
      method: 'GET',
      data: {
        name: name,
        page: page,
        limit: 6
      },
      success: function (res) {

        var goods = that.data.goods;
        if (res.data.goods.length < 6) {
          for (let i = 0; i < res.data.goods.length; i++) {
            goods.push(res.data.goods[i]);
          }
          that.setData({
            isReachBottom: true,
            load_more: '暂无更多商品',
            goods: goods
          });
        } else {
          for (let i = 0; i < res.data.goods.length; i++) {
            goods.push(res.data.goods[i]);
          }
          that.setData({
            page: ++page,
            load_more: '加载更多',
            goods: goods
          });
        }

      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  //用户分享
  onShareAppMessage: function () {
  }
})