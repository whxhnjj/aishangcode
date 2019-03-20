
import { Core } from '../../extend/core.js';
var core = new Core();

var app = getApp();

Page({

  data: {
    page: 1,
    isReachBottom: 1,
    hasMoreData: [],
    income: [],
    income_text: '暂无更多信息' 
  },

  onLoad: function (e) {
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      hasMoreData: [],
      income_text: '正在加载'
    })
    that.getScore(that.data.page, that.data.hasMoreData);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        hasMoreData: that.data.income
      })
      that.getScore(that.data.page, that.data.hasMoreData);
    }
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, hasMoreData: [] });
    that.getScore(that.data.page, that.data.hasMoreData);
  },

  //请求收入信息
  getScore: function (page, hasMoreData) {
    let that = this;
    core.request({
      url: 'api/customer/income',
      method: 'GET',
      data: {
        page: page,
        limit: 6
      },
      success: function (res) {

        if (page == 1) {
          that.setData({
            income: res.data.income,
            isReachBottom: 1,
          });
        }

        if (res.data.income.length < 6 ) {

          var n = hasMoreData;
          var income = res.data.income;
          for (let i = 0; i < income.length; i++) {
            income[i].confirm_time = that.formatTime(income[i].confirm_time);
            n.push(income[i]);
          }

          that.setData({ 
            isReachBottom: 0,
            income_text: '暂无更多信息',
            income: n,
          });
        } else {

          var n = hasMoreData;
          var income = res.data.income;
          for (let i = 0; i < income.length; i++) {
            income[i].confirm_time = that.formatTime(income[i].confirm_time);
            n.push(income[i]);
          }

          that.setData({
            income: n,
          });

          page++;

          that.setData({
            page: page,
            income_text: '加载更多'
          })
        }

      },
      fail: function (res) {
      }
    });
  },

  //格式化时间
  formatTime: function (v) {
    let that = this;
    var v = parseInt(v) * 1000;
    if (v < 0) { v = 0; }
    var dateObj = new Date(v);
    var month = dateObj.getMonth() + 1;
    var day = dateObj.getDate();
    var hours = dateObj.getHours();
    var minutes = dateObj.getMinutes();
    var seconds = dateObj.getSeconds();
    if (month < 10) {
      month = "0" + month;
    }
    if (day < 10) {
      day = "0" + day;
    }
    if (hours < 10) {
      hours = "0" + hours;
    }
    if (minutes < 10) {
      minutes = "0" + minutes;
    }
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    var UnixTimeToDate = dateObj.getFullYear() + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
    return UnixTimeToDate;
  },

  //首页跳转
  gotoIndex: function () {
    wx.switchTab({
      url: '../index/index'
    })
  }

})