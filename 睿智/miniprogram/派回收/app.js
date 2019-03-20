
import { Core } from 'extend/core.js';
var core = new Core();


App({
  
  globalData: {
    member_rule: 0,
    is_complete: 0
  },

  onLaunch:function(){
  },

  getMemberInfo:function(callback){
    let that = this;
    core.request({
      url: 'api/complete/member',
      method: 'GET',
      success: function (res) {
        callback && callback(res)
      },
      fail: function (res) {
      }
    })
  },

})