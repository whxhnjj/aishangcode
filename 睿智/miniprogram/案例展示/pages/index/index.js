
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    page: 1,
    isReachBottom: false,
    cases: [],
    page_text: '暂无更多案例',
    state:1
  },


  onLoad: function(){
    let that = this;
    that.getSwiper();
    that.getCaseClass();
    that.getCasesList(that.data.page, that.data.state);

    if (app.globalData.info){
      that.setData({
        logo: app.globalData.info.logo
      });
      wx.setNavigationBarTitle({
        title: app.globalData.info.name
      });
    }else{
      app.infoCallback = function(info){
        that.setData({
          logo: info.logo
        });
        wx.setNavigationBarTitle({
          title: app.globalData.info.name
        });
      }
    }
  },

  onShow: function ( ) {
    let that = this; 
    // that.setData({ page: 1, cases: [], isReachBottom: false });
    
  },

  //切换事件
  toggleState:function(e){
    let that = this;
    that.setData({
      state: e.currentTarget.dataset.state,
      page: 1,
      cases: [],
      isReachBottom: false,
      page_text: '暂无更多案例'
    })
    that.getCasesList(that.data.page,that.data.state);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      cases: [],
      isReachBottom: false
    })
    that.getCasesList(that.data.page, that.data.state);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getCasesList(that.data.page, that.data.state);
    }
  },

  //请求案例分类
  getCaseClass: function(){
    let that = this;
    core.request({
      url: 'api/index/categories',
      method: 'GET',
      success: function (res) {
        that.setData({
          categories: res.data.categories
        });
      },
      fail: function (res) {
      }
    });
  },

  //请求案例列表
  getCasesList: function (page,state) {
    let that = this;
    core.request({
      url: 'api/index/cases',
      method: 'GET',
      data: {
        cate_id: state,
        page: page,
        limit: 4
      },
      success: function (res) {

        var cases = that.data.cases;
        if (res.data.cases.length < 4) {
          for (let i = 0; i < res.data.cases.length; i++) {
            cases.push(res.data.cases[i]);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多案例',
            cases: cases
          });
        } else {
          for (let i = 0; i < res.data.cases.length; i++) {
            cases.push(res.data.cases[i]);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            cases: cases
          });
        }

      },
      fail: function (res) {
      }
    });
  },

  //获取轮播
  getSwiper:function(){
    let that = this;
    core.request({
      url: 'api/index/cases',
      method: 'GET',
      data:{
        is_recommend:1
      },
      success: function (res) {
        that.setData({
          swiper:res.data.cases
        });
      },
      fail: function (res) {
      }
    });
  },

  

  onShareAppMessage: function () {
  }

})