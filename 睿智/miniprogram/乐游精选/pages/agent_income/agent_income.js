import { Core } from '../../extend/core.js';
var core = new Core();

const util = require('../../utils/util.js')

Page({


  data: {
    total_income: 0,
    day_income: 0,
    page: 1,
    isReachBottom: false,
    page_text: '暂无更多收益',
    income: []
  },

  onLoad:function(){
    let that = this;
    that.getMemberInfo();
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, income: [], isReachBottom: false });
    that.getIncome();
    that.getIncomeList(that.data.page);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      income: [],
      isReachBottom: false
    })
    that.getIncomeList(that.data.page);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getIncomeList(that.data.page);
    }
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        that.setData({
          team_rule: res.data.member.team_rule
        });
      },
      fail: function (res) {
      }
    });
  },

  //获取收益列表
  getIncomeList:function(page){
    let that = this;
    core.request({
      url: 'api/member/cashlist',
      method: 'GET',
      data: {
        page: page,
        limit: 6
      },
      success: function (res) {

        var income = that.data.income;
        if (res.data.cash.length < 6) {
          for (let i = 0; i < res.data.cash.length; i++) {
            var list = res.data.cash[i];
            list.update_time = util.formatTime(list.update_time);
            list.amount = (list.amount / 100).toFixed(2);
            income.push(list);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多收益',
            income: income
          });
        } else {
          for (let i = 0; i < res.data.cash.length; i++) {
            var list = res.data.cash[i];
            list.update_time = util.formatTime(list.update_time);
            list.amount = (list.amount/100).toFixed(2);
            income.push(list);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            income: income
          });
        }
      },
      fail: function (res) {
      }
    });
  },

  //收益金额
  getIncome: function () {
    let that = this;
    core.request({
      url: 'api/member/cash',
      method: 'GET',
      success: function (res) {
        that.setData({
          total_income: (res.data.all/100).toFixed(2),
          day_income: (res.data.today/100).toFixed(2)
        });
      },
      fail: function (res) {
      }
    });
  }
})