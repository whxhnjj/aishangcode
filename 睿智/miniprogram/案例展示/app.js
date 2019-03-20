
import { Core } from 'extend/core.js';
var core = new Core();

App({
  
  globalData: {},

  onLaunch: function() {
    let that = this;
    that.getBasicInfo();
  },


  //获取基本信息
  getBasicInfo: function () {
    let that = this;
      core.request({
        url: 'api/index/info',
        success: function (res) {
          that.globalData.info = res.data.info;

          if (that.infoCallback) {
            that.infoCallback(res.data.info);
          }
          
        },
        fail: function (res) {
        }
      });
  }

});