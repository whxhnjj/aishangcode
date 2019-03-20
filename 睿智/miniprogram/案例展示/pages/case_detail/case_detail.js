
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl
  },

  onLoad:function(e){
    let that = this;
    that.setData({
      case_id:e.case_id,
      info: app.globalData.info
    });
    that.getCaseInfo(that.data.case_id);
  },

  // 拨打电话
  makePhone: function(e){
    let that = this;
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contacts
    });
  },

  //获取案例
  getCaseInfo:function(id){
    let that = this;
    core.request({
      url: 'api/index/details',
      method: 'GET',
      data: {
        id:id
      },
      success: function (res) {
        wx.setNavigationBarTitle({
          title: res.data.cases.name
        });
        wx.pageScrollTo({ scrollTop: 0 });
        var cases = res.data.cases;
        WxParse.wxParse('content', 'html', cases.content, that, 5);
        that.setData({
          cases: res.data.cases,
          sibling: res.data.sibling
        });
        
      },
      fail: function (res) {
        that.setData({fail_state: true});
        wx.showToast({
          title: res.msg,
          icon:'none'
        });
      }
    });
  },



  // 切换案例
  toggleCase: function(e){
    let that = this;    
    if (e.target.dataset.id == 0){
      wx.showToast({
        title: '暂无更多案例',
        icon: 'none'  
      });
    }else{
      that.getCaseInfo(e.target.dataset.id);
    }
  },


  onShareAppMessage: function () {
  }

})