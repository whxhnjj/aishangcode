
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    feedback: ['功能意见', '界面意见', '您的新需求', '操作意见', '投诉', '其他'],
    fd_index: 0,
    fd_cont: '',
    phone_num: '',
  },

  bindPickerChange: function (e) {
    let that = this;
    that.setData({ fd_index: e.detail.value })
  },

  formSubmit:function(e){
    let that = this;
    if (!e.detail.value.fd_cont) {
      wx.showToast({
        title: '反馈内容不能为空',
        icon: 'none'
      });
      return;
    }

    if (!e.detail.value.phone_num) {
      wx.showToast({
        title: '联系方式不能为空',
        icon: 'none'
      });
      return;
    }

    core.request({
      url: 'api/info/feedback',
      data: {
        type: that.data.feedback[that.data.fd_index],
        contacts: e.detail.value.phone_num,
        content: e.detail.value.fd_cont
      },
      method: 'POST',
      success: function (res) {

        wx.showToast({
          title: '提交成功',
        });

        setTimeout(function(){
          wx.switchTab({
            url: '../user/user'
          });
        },500);

      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });
  }

})