
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    
  },

  
  onLoad: function () {
    
  },

  
  onShow: function () {
    let that = this;
    that.getMemberInfo();
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        if (res.data.member.avatarurl == '') {
          that.setData({
            avatarurl: core.baseUrl + 'static/images/member.png',
            nickname: '点击获取头像',
          });
        } else {
          that.setData({
            avatarurl: res.data.member.avatarurl,
            nickname: res.data.member.nickname,
          });
        }
        that.setData({
          member: res.data.member
        });
      },
      fail: function (res) {
      }
    });
  },

  //更新用户信息
  onGotUserInfo: function (e) {
    let that = this;
    if (e.detail.errMsg == "getUserInfo:ok"){
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
    }
  },

  //修改信息页面跳转
  goToQrcode: function(){
    wx.navigateTo({
      url: '../qrcode/qrcode',
    });
  },

})