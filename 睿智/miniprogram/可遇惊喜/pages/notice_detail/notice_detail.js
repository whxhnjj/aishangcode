
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

const util = require('../../utils/util.js')

Page({

  data: {

  },

  onLoad: function (e) {
    let that = this;
    that.setData({id : e.id});
    that.getNoticeDetail();
  },

  getNoticeDetail:function(){
    let that = this;
    core.request({
      url: 'api/info/nocdetails',
      method: 'GET',
      data:{
        id: that.data.id
      },
      success: function (res) {
        var notice = res.data.notice;
        WxParse.wxParse('content', 'html', notice.content, that, 5);
        notice.add_time = util.formatTime(notice.add_time);
        that.setData({
          notice: notice
        });
      },
      fail: function (res) {
      }
    });
  }
})