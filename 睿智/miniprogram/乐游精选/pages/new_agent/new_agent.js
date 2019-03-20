
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  formSubmit: function (e) {
    let that = this;
    core.request({
      url: 'api/member/cluster',
      method: 'POST',
      data:{
        team_name: e.detail.value.team_name,
        team_desc: e.detail.value.team_desc,
      },
      success: function (res) {
        wx.showToast({
          title: '创建成功',
        });
        setTimeout(function(){
          wx.navigateBack({
            delta: 1
          });
        },500);
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