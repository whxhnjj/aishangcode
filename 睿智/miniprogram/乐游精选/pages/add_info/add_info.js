
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {

  },


  onLoad: function (e) {
    let that = this;
    that.setData({
      type: e.type
    });
  },

  
  onShow: function () {

  },


  //更新用户信息
  formSubmit: function (e) {
    let that = this;
    var data,url;
    if (that.data.type == 1){
      data = {
        realname: e.detail.value.realname,
      }
      url = 'realname';
    }else{
      data = {
        mobile: e.detail.value.mobile,
      }
      url = 'mobile';
    }
    core.request({
      url: 'api/member/'+url,
      data: data,
      method: 'POST',
      success: function (res) {
        if (that.data.type == 1){
          app.globalData.member.realname = e.detail.value.realname
        }else{
          app.globalData.member.mobile = e.detail.value.mobile
        }
        wx.navigateBack({
          delta: 1
        });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon:'none'
        });
      }
    });
  }
  
})