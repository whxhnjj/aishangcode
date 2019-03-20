//index.js

import { Core } from '../../extend/core.js';
var core = new Core();


Page({
  data: {
    hostName: core.baseUrl,
    categories: [],
    goods: [],
    page: 1,
    isReachBottom: false,
    page_text: '正在加载',
    index: 0,
    scroll_left: 0
  },


  onLoad: function(){
    let that = this;
    that.setData({
      page: 1,
      goods: [],
      isReachBottom: false,
      index: 0
    });
    that.getHome();
    that.getGoodsType();
  },

  //首页获取
  getHome:function(){
    let that = this;
    core.request({
      url: 'api/index/home',
      method: 'GET',
      success: function (res) {
        that.setData({     
          swiper: res.data.swiper,
          shop_logo: res.data.shop.logo,      
        });
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



  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      goods: [],
      isReachBottom: false
    })
    if (that.data.categories.length>0){
      that.getGoodsList(that.data.categories[that.data.index].id, that.data.page);
    }
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getGoodsList(that.data.categories[that.data.index].id, that.data.page);
    }
  },

  // 请求商品分类
  getGoodsType: function () {
    let that = this;
    core.request({
      url: 'api/goods/group_lists',
      method: 'GET',
      success: function (res) {
        var categories = res.data.group;
        if (categories.length > 0){
          categories[that.data.index].selected = true;
          that.setData({
            categories: categories
          });
          that.getGoodsList(that.data.categories[that.data.index].id, that.data.page);
        }
      },
      fail: function (res) {
      }
    });
  },

  //更新商品分类
  updataGoodsList: function (e) {
    let that = this;
    var index = e.currentTarget.dataset.index;
    var id = e.currentTarget.dataset.id;
    var categories = that.data.categories;

    for (let i = 0; i < categories.length; i++) {
      categories[i].selected = false;
    }
    categories[index].selected = true;

    //居中偏移
    var query = wx.createSelectorQuery();
    query.select('.item_' + index).boundingClientRect(function (rect) {
      var offset_left = e.currentTarget.offsetLeft;
      var scroll_left = offset_left - (375 - rect.width) / 2 + 15;
      that.setData({
        scroll_left: scroll_left
      });
    }).exec();;


    that.setData({
      goods: [],
      page: 1,
      isReachBottom: false,
      categories: categories,
      index: index
    });

    that.getGoodsList(id, that.data.page);

  },

  //请求商品列表
  getGoodsList: function (cate_id, page) {
    let that = this;
    core.request({
      url: 'api/goods/lists',
      method: 'GET',
      data: {
        group_id: cate_id,
        page: page,
        limit: 6
      },
      success: function (res) {

        var goods = that.data.goods;
        if (res.data.goods.length < 6) {
          for (let i = 0; i < res.data.goods.length; i++) {
            goods.push(res.data.goods[i]);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多商品',
            goods: goods
          });
        } else {
          for (let i = 0; i < res.data.goods.length; i++) {
            goods.push(res.data.goods[i]);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            goods: goods
          });
        }

      },
      fail: function (res) {
      }
    });

  },

  //搜索
  searchSubmit: function(e){
    let that = this;
    if (!e.detail.value.name) {
      wx.showToast({
        title: '搜索内容不能为空',
        icon: 'none'
      });
      return;
    }
    wx.navigateTo({
      url: '../search/search?name=' + e.detail.value.name
    });
  },

  onShareAppMessage: function () {
  }
})