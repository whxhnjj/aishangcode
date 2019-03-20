
import { Core } from '../../extend/core.js';
var core = new Core();

var app = getApp();

Page({

  data: {
    page: 1,
    isReachBottom: 1,
    hasMoreData: [],
    integral: [],
    page_text: '暂无更多信息'
  
  },

  onLoad: function (e) {
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      hasMoreData: [],
      page_text: '正在加载'
    })
    that.getScore(that.data.page, that.data.hasMoreData);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        hasMoreData: that.data.integral
      })
      that.getScore(that.data.page, that.data.hasMoreData);
    }
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, hasMoreData: [] });
    that.getScore(that.data.page, that.data.hasMoreData);
    that.getMemberInfo();
  },

  //请求用户积分
  getMemberInfo: function () {
    let that = this;
    app.getMemberInfo(function (res) {
      that.setData({
        member: res.data.member
      });
    })
  },

  //请求积分信息
  getScore: function (page, hasMoreData) {
    let that = this;
    core.request({
      url: 'api/customer/integral',
      method: 'GET',
      data:{
        page:page,
        limit: 5
      },
      success: function (res) {

        if (page == 1) {
          that.setData({
            integral: res.data.integral,
            isReachBottom: 1,
          });
        }

        if (res.data.integral.length < 5) {
          var n = hasMoreData;
          var integral = res.data.integral;
          for (let i = 0; i < integral.length; i++) {
            integral[i].update_time = that.formatTime(integral[i].update_time);
            n.push(integral[i]);
          }

          that.setData({ 
            isReachBottom: 0,
            page_text: '暂无更多信息',
            integral: n,
           });
      
        } else {

          var n = hasMoreData;
          var integral = res.data.integral;
          for (let i = 0; i < integral.length; i++) {
            integral[i].update_time = that.formatTime(integral[i].update_time);
            n.push(integral[i]);
          }

          that.setData({
            integral: n,
          });

          page++;

          that.setData({
            page: page,
            page_text: '加载更多'
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