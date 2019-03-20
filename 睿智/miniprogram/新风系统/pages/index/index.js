//index.js

import { Core } from '../../extend/core.js';
var core = new Core();
var original;
var page = 1 

Page({
  data: {
    hostName: core.baseUrl,
    currentTab: 0,
    goods: [],
    isReachBottom: 1,
    load_more: '加载更多',
    good_counts: 0
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    page = 1;
    that.getProList([]);
    that.getHome();
    // that.getGoodsCount();
    // that.getSwiper();
    // that.getBanner();
    // that.getPro('promotion');
    // that.getPro('hot');
    // that.getPro('new');
    // that.getPro('recommend');
    wx.stopPullDownRefresh();
  },

  //监听用户上拉触底
  onReachBottom: function () {
    if (this.data.isReachBottom) {
      original = this.data.goods;
      this.getProList(original);
    }
  },

  onLoad: function(){
    let that = this;
    that.getHome();
    // that.getGoodsCount();
    // that.getShopLogo();
    // that.getSwiper();
    // that.getBanner();
    // that.getPro('promotion');
    // that.getPro('hot');
    // that.getPro('new');
    // that.getPro('recommend');
  },

  //首页获取
  getHome:function(){
    let that = this;
    core.request({
      url: 'api/index/home',
      method: 'GET',
      success: function (res) {
        that.setData({
          good_counts: res.data.count,
          swiper: res.data.swiper,
          shop_logo: res.data.shop.logo,
          banner: res.data.banner,
          hot: res.data.hot,
          new: res.data.new,
          promotion: res.data.promotion,
          recommend: res.data.recommend
        });
        wx.setNavigationBarTitle({
          title: res.data.shop.name
        })
      },
      fail: function (res) {
      }
    });

  },

  //获取商品数量
  getGoodsCount: function(){
    let that = this;
    core.request({
      url: 'api/goods/counts',
      method: 'GET',
      success: function (res) {
        that.setData({
          good_counts: res.data.count
        })
      },
      fail: function (res) {
      }
    });
  },

  //获取店铺logo
  getShopLogo: function(){
    let that = this;
    core.request({
      url: 'api/shop/fetch',
      method: 'GET',
      success: function (res) {
        that.setData({
          shop_logo: res.data.shop.logo
        })
        wx.setNavigationBarTitle({
          title: res.data.shop.name
        })
      },
      fail: function (res) {
        
      }
    });
  },

  //请求swiper
  getSwiper: function(){
    let that = this;
    core.request({
      url: 'api/index/swiper',
      method: 'GET',
      success: function (res) {
        that.setData({
          swiper: res.data.swiper
        })
      },
      fail: function (res) {
  
      }
    });
  },

  //获取banner
  getBanner: function(){
    let that = this;
    core.request({
      url: 'api/index/banner',
      method: 'GET',
      success: function (res) {
        that.setData({
          banner:res.data.banner
        })
      },
      fail: function (res) {

      }
    });
  },

  //获取分类商品
  getPro: function(a){
    let that = this;
    core.request({
      url: 'api/index/'+a,
      method: 'GET',
      success: function (res) {
        that.setData({
          [a] : res.data
        })
      },
      fail: function (res) {
     
      }
    });
  },

 

  //获取全部商品列表
  getProList: function (original) {
    let that = this;
    core.request({
      url: 'api/goods/lists',
      data: {
        page:page
      },
      method: 'GET',
      success: function (res) {
        if (page == 1) {
          that.setData({
            goods: res.data.goods,
            isReachBottom: 1,
          });
        }

        if (res.data.goods.length == 0) {
          that.setData({ isReachBottom: 0, load_more: '已到达我的底线' });
        } else {
          var n = original;
          for (var i = 0; i < res.data.goods.length; i++) {
            n.push(res.data.goods[i])
          }
          that.setData({
            goods: n,
          });
          page++;
        }
    
      },
      fail: function (res) {

      }
    });
  },

  switchTab: function (e) {
    // console.log(e)
    let that = this;
    page = 1;
    that.setData({ isReachBottom: 1, load_more: '加载更多' });
    let tab = e.currentTarget.id;
    if (tab === 'tableft') {
      that.setData({ currentTab: 0 });
    } else if (tab === 'tabright') {
      that.setData({ currentTab: 1 });
      that.getProList([]);
    }
  },

  onShareAppMessage: function () {
  }
})