<?php
    include __PUBLIC_DIR__."/views/common_header.php";
?>

<h1>Test</h1>

<div v-cloak id="app-test">
  <ul>
    <li v-for="todo in todos">
      {{ todo.text }}
    </li>
  </ul>
</div>



<script type="text/javascript">
new Vue({
  el: '#app-test',
  data: {
    msg:'aaaaaaa',
    todos: [
      { text: 'Learn JavaScript' },
      { text: 'Learn Vue.js' },
      { text: 'Build Something Awesome' }
    ]
  }
})
</script>
