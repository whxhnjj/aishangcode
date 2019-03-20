
import { Core } from '../../extend/core.js';
var core = new Core();

const util = require('../../utils/util.js')

Page({

  data: {
    page: 1,
    isReachBottom: false,
    page_text: '暂无更多记录',
    record: []
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, record: [], isReachBottom: false });
    that.getRecordList(that.data.page);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      record: [],
      isReachBottom: false
    })
    that.getRecordList(that.data.page);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getRecordList(that.data.page);
    }
  },

  //获取提现列表
  getRecordList: function (page) {
    let that = this;
    core.request({
      url: 'api/member/withlist',
      method: 'GET',
      data: {
        page: page,
        limit: 8
      },
      success: function (res) {

        var record = that.data.record;
        if (res.data.with.length < 8) {
          for (let i = 0; i < res.data.with.length; i++) {
            var list = res.data.with[i];
            list.apply_time = util.formatTime(list.apply_time);
            list.bank_info.bank_four = list.bank_info.bank_no.slice(-4);
            list.apply_amount = (list.apply_amount / 100).toFixed(2);
            record.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多记录',
            record: record
          });
        } else {
          for (let i = 0; i < res.data.with.length; i++) {
            var list = res.data.with[i];
            list.apply_time = util.formatTime(list.apply_time);
            list.bank_info.bank_four = list.bank_info.bank_no.slice(-4);
            list.apply_amount = (list.apply_amount / 100).toFixed(2);
            record.push(list);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            record: record
          });
        }
      },
      fail: function (res) {
      }
    });
  }
 
})