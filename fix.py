import sys

def process():
    with open('resources/views/kepengurusan/anggota/proker/show.blade.php', 'r', encoding='utf-8') as f:
        show_content = f.read().splitlines()
    
    with open('resources/views/kepengurusan/anggota/proker/progress.blade.php', 'r', encoding='utf-8') as f:
        prog_content = f.read().splitlines()

    # Find Riwayat Progres Full in show.blade.php
    show_start = -1
    for i, line in enumerate(show_content):
        if '<!-- Riwayat Progres Full -->' in line:
            show_start = i
            break
            
    # extract timeline block (87 lines based on our previous file view)
    timeline_block = show_content[show_start:show_start+87]

    # find where to delete in progress.blade.php
    prog_del_start = -1
    for i, line in enumerate(prog_content):
        if '<!-- Riwayat Jurnal -->' in line:
            prog_del_start = i
            break
            
    prog_del_end = -1
    for i in range(prog_del_start, len(prog_content)):
        if prog_content[i].strip() == '<!-- Kolom Kanan: Info Ringkas Progres -->':
            prog_del_end = i - 2
            break
            
    # delete the block
    new_prog = prog_content[:prog_del_start] + prog_content[prog_del_end:]
    
    # Actually, we need to insert it at the end of Kolom Kanan
    insert_pos = -1
    for i in range(len(new_prog)-1, -1, -1):
        if new_prog[i].strip() == '@endsection':
            insert_pos = i - 2
            break
            
    final_prog = new_prog[:insert_pos] + [''] + timeline_block + new_prog[insert_pos:]
    
    with open('resources/views/kepengurusan/anggota/proker/progress.blade.php', 'w', encoding='utf-8') as f:
        f.write('\n'.join(final_prog) + '\n')
        
process()
