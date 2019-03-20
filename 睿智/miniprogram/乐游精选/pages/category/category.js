
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    goods: [],
    page: 1,
    isReachBottom: false,
    page_text: '正在加载',
    index:0,
    scroll_left:0
  },

  onLoad: function () {
    let that = this;
    that.setData({ 
      page: 1, 
      goods: [], 
      isReachBottom: false,
      index:0
    });
    that.getGoodsType();
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
    that.getGoodsList(that.data.categories[that.data.index].id,that.data.page);
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
  getGoodsType: function(){
    let that = this;
    core.request({
      url: 'api/goods/categories',
      method: 'GET',
      success: function (res) {
        var categories = res.data.categories;

        if (app.globalData.cate_id) {
          for (let i = 0; i < categories.length ; i++){
            if (categories[i].id == app.globalData.cate_id){
              that.setData({
                index:i
              });
            }
          }
        }
        
        categories[that.data.index].selected = true;
        that.setData({
          categories: categories
        });
        that.getGoodsList(that.data.categories[that.data.index].id,that.data.page);
      },
      fail: function (res) {
      }
    });
  },

  //更新商品分类
  updataGoodsList:function(e){
    let that = this;
    var index = e.currentTarget.dataset.index;
    var id = e.currentTarget.dataset.id;
    var categories = that.data.categories;

    for (let i = 0; i < categories.length; i++){
      categories[i].selected = false;
    }
    categories[index].selected = true;

    //居中偏移
    var query = wx.createSelectorQuery();
    query.select('.item_'+index).boundingClientRect(function (rect) {
      var offset_left = e.currentTarget.offsetLeft;
      var scroll_left = offset_left - (375 - rect.width) / 2 + 15;
      that.setData({
        scroll_left: scroll_left
      });
    }).exec();;

    
    that.setData({
      goods:[],
      page:1,
      isReachBottom: false,
      categories: categories,
      index: index
    });

    that.getGoodsList(id,that.data.page);

  },

  //请求商品列表
  getGoodsList: function (cate_id, page) {
    let that = this;
    core.request({
      url: 'api/goods/lists',
      method: 'GET',
      data:{
        cate_id: cate_id,
        page: page,
        limit: 6
      },
      success: function (res) {

        var goods = that.data.goods;
        if( res.data.goods.length < 6  ){
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);

            //新品 、结束
            if( list.begin_time != 0 ){
              var begin_time = list.begin_time * 1000;
              var end_time = list.end_time * 1000;
              var now_time = new Date().getTime();
              if (now_time - begin_time < 3 * 24 * 60 * 60 * 1000 && now_time - begin_time > 0) {
                list.class_state = 1; //新品
              }
              if (now_time > end_time) { list.class_state = 3; }//已结束
            }
            goods.push(list);

          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多商品',
            goods: goods
          });
        }else{
          for (let i = 0; i < res.data.goods.length; i++) {
            var list = res.data.goods[i];
            list.price = (list.price / 100).toFixed(2);
            list.market_price = (list.market_price / 100).toFixed(2);
            list.back_cash = (list.back_cash / 100).toFixed(2);

            //新品 、结束
            if (list.begin_time != 0) {
              var begin_time = list.begin_time * 1000;
              var end_time = list.end_time * 1000;
              var now_time = new Date().getTime();
              if (now_time - begin_time < 3 * 24 * 60 * 60 * 1000 && now_time - begin_time > 0) {
                list.class_state = 1; //新品
              }
              if (now_time > end_time) { list.class_state = 3; }//已结束
            }
            
            goods.push(list);
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

  onShareAppMessage: function () {
    let that = this;
    return {
      path: '/pages/index/index?scene=s-' + app.globalData.member.id
    }
  }
})