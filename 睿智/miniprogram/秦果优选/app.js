
import { Core } from 'extend/core.js';
var core = new Core();

App({

  onLaunch: function () {
    core.getToken();
  },

  globalData: {
    isIphoneX: false,
  },

  onShow: function () {
    let that = this;
    wx.getSystemInfo({
      success: res => {
        // console.log('手机信息res'+res.model)
        let modelmes = res.model;
        if (modelmes.search('iPhone X') != -1) {
          that.globalData.isIphoneX = true
        }
      }
    })
  }

})