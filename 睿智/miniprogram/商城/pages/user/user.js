var app = getApp;
import { Core } from '../../extend/core.js';
var core = new Core();


Page({

  data: {
    nickname: '点击获取头像',
    avatarurl: core.baseUrl+'static/images/member.png'
  },

  onLoad: function (options) {
    let that =this;
    that.requestTradeNum();
    core.request({
      url: 'api/member/fetch',
      method: 'GET',
      success: function (res) {
        if (res.data.member.avatarurl && res.data.member.nickname){
          that.setData({
            avatarurl: res.data.member.avatarurl,
            nickname: res.data.member.nickname
          })
        }
      },
      fail: function (res) {
      }
    });
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.requestTradeNum();
    wx.stopPullDownRefresh()
  },

  //更新用户信息
  onGotUserInfo: function (e) {
    let that = this;
    core.request({
      url: 'api/member/update',
      data: {
        avatarurl: e.detail.userInfo.avatarUrl,
        nickname: e.detail.userInfo.nickName
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          avatarurl: res.data.member.avatarurl,
          nickname: res.data.member.nickname
        })
      },
      fail: function (res) {
      }
    });
  },

  //请求订单分类数量
  requestTradeNum: function(){
    let that = this;
    core.request({
      url: 'api/trade/counts',
      method: 'GET',
      success: function (res) {
        that.setData({
          count: res.data.count
        })
      },
      fail: function (res) {
      }
    });
  }

})