
import { Core } from 'extend/core.js';
var core = new Core();

App({
  globalData: {
    isIphone: false,
  },

  onLaunch: function (e) {
    let that = this;
    if (e.query.scene) {
      var scene = e.query.scene.split('-');
      console.log(scene);
      switch (scene[0]) {
        case 'q':
        case 's':
        case 'p':
          //从属关联qrcode
          wx.setStorageSync('member_id', scene[1]);
          that.sendShareMsg('subordinate', { member_id: scene[1] });
          break;
        case 't':
          //加入团队team
          that.sendShareMsg('invite', { creator_id: scene[1], detect: 1 });
          break;
      }
    }
  },

  //e提交
  sendShareMsg: function (url, data) {
    let that = this;
    core.request({
      url: 'api/member/' + url,
      method: 'POST',
      data: data,
      silent: true,
      success: function (res) {
        if (url == 'invite') {
          if (data.detect) {
            wx.showModal({
              title: '团队邀请',
              content: res.data.creator.realname ? res.data.creator.realname : res.data.creator.nickname + '邀请你加入' + res.data.creator.team_name + '团队',
              success: function (res) {
                if (res.confirm) {
                  that.sendShareMsg('invite', { creator_id: data.creator_id });
                }
              }
            })
          } else {
            wx.showToast({
              title: '加入成功',
            });
          }
        }

        console.log('s', res)
      },
      fail: function (res) {
        console.log('f', res)
      }
    });
  },

  onShow: function () {
    let that = this;
    that.phoneType();
    that.getMemberInfo();
  },

  //手机机型判断
  phoneType: function () {
    let that = this;
    wx.getSystemInfo({
      success: res => {
        let modelmes = res.model;
        if (modelmes.search('iPhone X') != -1) {
          that.globalData.isIphone = true
        }
      }
    });
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      silent: true,
      success: function (res) {
        that.globalData.member = res.data.member;
      },
      fail: function (res) {
      }
    });
  },

})