<template>
  <CFooter :fixed="false">
    <div>
      <a href="http://arhal.co.in" target="_blank">Arhal</a>
      <span class="ml-1">&copy; {{new Date().getFullYear()}} Carewel.</span>
    </div>
    
  </CFooter>
</template>

<script>
export default {
  name: 'TheFooter'
}
</script>
