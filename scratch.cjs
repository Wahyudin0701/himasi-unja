const fs = require('fs');
let div = fs.readFileSync('c:/laragon/www/himasi-management/resources/views/division.blade.php', 'utf8');
let pub = fs.readFileSync('c:/laragon/www/himasi-management/resources/views/layouts/public.blade.php', 'utf8');

const startTag = '<!-- ══════ TECH × DIGITAL × INNOVATION – OPTIMIZED CANVAS ══════ -->';
let startIndex = div.indexOf(startTag);
let endIndex = div.indexOf('</script>', startIndex) + 9;

if (startIndex === -1) {
    console.log('Start tag not found in division.blade.php');
} else {
    console.log('Tags found, processing...');
    let bgCode = div.substring(startIndex, endIndex);
    
    // Replace absolute with fixed
    bgCode = bgCode.replace('<div class="absolute inset-0 overflow-hidden pointer-events-none" style="z-index:0;contain:paint">', '<div class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index:0;contain:paint">');

    // Remove from div
    let newDiv = div.slice(0, startIndex) + div.slice(endIndex);
    
    // Clean up wrapper relative in division
    newDiv = newDiv.replace('<div class="relative">\r\n    \r\n\r\n<!-- Hero Section -->', '<!-- Hero Section -->');
    newDiv = newDiv.replace('<div class="relative">\n    \n\n<!-- Hero Section -->', '<!-- Hero Section -->');
    newDiv = newDiv.replace('<div class="relative">\r\n<!-- Hero Section -->', '<!-- Hero Section -->');
    newDiv = newDiv.replace(/<div class="relative">\s*<!-- Hero Section -->/, '<!-- Hero Section -->');
    
    // Clean up end wrapper in division
    newDiv = newDiv.replace(/<\/div><!-- end full-page digital wrapper -->\s*@endsection/, '@endsection');
    
    fs.writeFileSync('c:/laragon/www/himasi-management/resources/views/division.blade.php', newDiv);

    // Insert into pub
    let bodyMatch = pub.match(/<body[^>]*>/);
    if(bodyMatch) {
        let insertPos = bodyMatch.index + bodyMatch[0].length;
        let newPub = pub.slice(0, insertPos) + '\n\n' + bgCode + '\n' + pub.slice(insertPos);
        fs.writeFileSync('c:/laragon/www/himasi-management/resources/views/layouts/public.blade.php', newPub);
        console.log('Done moving code to public layout');
    }
}
