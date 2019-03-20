
import { Core } from '../../extend/core.js';
var core = new Core();

const util = require('../../utils/util.js')

Page({

  data: {
    total_integral:0,
    page: 1,
    isReachBottom: false,
    page_text: '暂无更多积分信息',
    integral: []
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, integral: [], isReachBottom: false });
    that.getTotalIntegral();
    that.getIntegralList(that.data.page);
  },

  //积分总金额
  getTotalIntegral:function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        that.setData({
          total_integral: res.data.member.integral
        });
      },
      fail: function (res) {
      }
    });
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      integral: [],
      isReachBottom: false
    })
    that.getIntegralList(that.data.page);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getIntegralList(that.data.page);
    }
  },

  //请求积分列表
  getIntegralList: function (page) {
    let that = this;
    core.request({
      url: 'api/member/intlist',
      method: 'GET',
      data: {
        page: page,
        limit: 6
      },
      success: function (res) {

        var integral = that.data.integral;
        if (res.data.int.length < 6) {
          for (let i = 0; i < res.data.int.length; i++) {
            var list = res.data.int[i];
            list.update_time = util.formatTime(list.update_time);
            integral.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多积分信息',
            integral: integral
          });
        } else {
          for (let i = 0; i < res.data.int.length; i++) {
            var list = res.data.int[i];
            list.update_time = util.formatTime(list.update_time);
            integral.push(list);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            integral: integral
          });
        }
      },
      fail: function (res) {
      }
    });
  },

  //首页
  goIndex:function(){
    wx.switchTab({
      url: '../index/index'
    });
  }

})