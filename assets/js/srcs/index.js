import UIkit from 'uikit'; // import uikit
import Icons from 'uikit/dist/js/uikit-icons'; // import uikit icons
UIkit.use(Icons); // use the Icon plugin
window.UIkit = UIkit; // Make uikit available in window for inline scripts

// import Macy from 'macy';
import * as MasonryGrid from 'macy'; // we use macy to fix bug in uk-grid: masonry
window.MasonryGrid = MasonryGrid