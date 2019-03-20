
import { Core } from '../../extend/core.js';
var core = new Core();

const util = require('../../utils/util.js')

Page({

  data: {

  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      id:e.id
    });
  },

  onShow: function () {
    let that = this;
    that.getRecordList();
  },

  //获取提现详情
  getRecordList: function () {
    let that = this;
    core.request({
      url: 'api/member/withdetails',
      method: 'GET',
      data: {
        id: that.data.id,
      },
      success: function (res) {
        var details = res.data.with;
        details.apply_time = util.formatTime(details.apply_time);
        details.handle_time = util.formatTime(details.handle_time);
        details.bank_info.bank_four = details.bank_info.bank_no.slice(-4);
        details.apply_amount = (details.apply_amount/100).toFixed(2);
        details.amount = (details.amount / 100).toFixed(2);
        details.procedures = (details.apply_amount - details.amount).toFixed(2);
        that.setData({
          details: details
        });
      },
      fail: function (res) {
      }
    });
  }

})