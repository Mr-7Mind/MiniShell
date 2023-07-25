#!/bin/bash

function merge_files() {
    nama_file_output=$1
    shift

    for file_input in "$@"; do
        cat "$file_input" >> "$nama_file_output"
        echo "File $file_input berhasil digabungkan ke $nama_file_output"
    done

    echo -e "\e[1;32mPenggabungan file berhasil...\e[0m"
}

function show_menu() {
  echo -e "\033[1;32mPowered By RibelCyberTeam (Mr.7Mind)\033[0m"
  echo -e "\033[1;32m:'######::'####:'##::::'##:'########::'##:::::::'########:'########::'#######:::'#######::'##::::::::'######::\033[0m"
  echo -e "\033[1;32m'##... ##:. ##:: ###::'###: ##.... ##: ##::::::: ##.....::... ##..::'##.... ##:'##.... ##: ##:::::::'##... ##:\033[0m"
  echo -e "\033[1;32m ##:::..::: ##:: ####'####: ##:::: ##: ##::::::: ##:::::::::: ##:::: ##:::: ##: ##:::: ##: ##::::::: ##:::..::\033[0m"
  echo -e "\033[1;32m. ######::: ##:: ## ### ##: ########:: ##::::::: ######:::::: ##:::: ##:::: ##: ##:::: ##: ##:::::::. ######::\033[0m"
  echo -e "\033[1;32m:..... ##:: ##:: ##. #: ##: ##.....::: ##::::::: ##...::::::: ##:::: ##:::: ##: ##:::: ##: ##::::::::..... ##:\033[0m"
  echo -e "\033[1;32m'##::: ##:: ##:: ##:.:: ##: ##:::::::: ##::::::: ##:::::::::: ##:::: ##:::: ##: ##:::: ##: ##:::::::'##::: ##:\033[0m"
  echo -e "\033[1;32m. ######::'####: ##:::: ##: ##:::::::: ########: ########:::: ##::::. #######::. #######:: ########:. ######::\033[0m"
  echo -e "\033[1;32m:......:::....::..:::::..::..:::::::::........::........:::::..::::::.......::::.......:::........:::......:::\033[0m"
  echo -e "\033[1;32m1. Memotong File\033[0m"
  echo -e "\033[1;32m2. Menghapus Duplikat\033[0m"
  echo -e "\033[1;32m3. Filter Sesuai Domain\033[0m"
  echo -e "\033[1;32m4. Sortir Berdasarkan Abjad\033[0m"
  echo -e "\033[1;32m5. Menggabungkan File List\033[0m"
  echo -e "\033[1;32m6. Ekstrak Domain Dari File\033[0m"
  echo -e "\033[1;32m7. Keluar\033[0m"
}

function potong_file() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file Tidak Ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mJumlah Yang Diinginkan: \e[0m' line_per_file
  if ! [[ "$line_per_file" =~ ^[0-9]+$ ]]; then
    echo -e "\e[1;31mInput tidak valid. Harap masukkan angka.\e[0m"
    return
  fi

  mkdir -p potongan_files

  total_lines=$(wc -l < "$input_file")
  file_counter=1

  while [ "$total_lines" -gt 0 ]; do
    lines_to_move=$((total_lines > line_per_file ? line_per_file : total_lines))
    sed -n "1,${lines_to_move}p" "$input_file" > "potongan_files/file_${file_counter}.txt"
    sed -i "1,${lines_to_move}d" "$input_file"
    total_lines=$((total_lines - lines_to_move))
    file_counter=$((file_counter + 1))
  done

  echo -e "\e[1;32mMemotong Line Selesai... \e[0m"
}

function ekstrak_domain() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  awk -F '/' '{ print $NF }' "$input_file" > "$output_file"

  echo -e "\e[1;32mEkstraksi Domain Selesai... \e[0m"
}

function hapus_baris_duplikat() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  sort "$input_file" | uniq > "$input_file.tmp"
  mv "$input_file.tmp" "$input_file"

  echo -e "\e[1;32mBaris duplikat dalam file $input_file telah dihapus.\e[0m"
}

function filter_domain() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  grep -Eo '([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})' "$input_file" | \
  while read -r domain; do
    extension="${domain##*.}"

    if [[ "$extension" =~ ^(php|html|htm|xml|zip|aspx|php1|php2|php3|php4|php5|php6|php7)$ ]]; then
      echo "File $domain diabaikan karena ekstensi $extension terdapat dalam daftar hitam."
    else
      echo "$domain" >> "$output_file"
    fi
  done

  echo -e "\e[1;32mFilter Domain Selesai... \e[0m"
}

function sort_by_alphabet() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  sort "$input_file" > "$output_file"

  echo -e "\e[1;32mSort By Alphabet Selesai... \e[0m"
}

function menu_merge_files() {
  read -p $'\e[1;34mMasukkan Nama File Output: \e[0m' file_output
  if [ -z "$file_output" ]; then
    echo -e "\e[1;31mNama file output tidak boleh kosong.\e[0m"
    return
  fi

  files_to_merge=()
  while true; do
    read -p $'\e[1;34mMasukkan Nama File (tekan Enter untuk selesai): \e[0m' file_input
    if [ -z "$file_input" ]; then
      break
    fi

    if [ ! -f "$file_input" ]; then
      echo -e "\e[1;31mFile $file_input tidak ditemukan.\e[0m"
    else
      files_to_merge+=("$file_input")
    fi
  done

  if [ ${#files_to_merge[@]} -eq 0 ]; then
    echo -e "\e[1;31mTidak ada file yang dimasukkan.\e[0m"
    return
  fi

  merge_files "$file_output" "${files_to_merge[@]}"
}

while true; do
  show_menu
  read -p $'\e[1;32mPilih menu: \e[0m' menu_choice

  case $menu_choice in
    1)
      potong_file
      ;;
    2)
      hapus_baris_duplikat
      ;;
    3)
      filter_domain
      ;;
    4)
      sort_by_alphabet
      ;;
    5)
      menu_merge_files
      ;;
    6)
      ekstrak_domain
      ;;
    7)
      echo -e "\e[1;33mTerima kasih, Follow github.com/Mr-7Mind \e[0m"
      exit 0
      ;;
    *)
      echo -e "\e[1;31mPilihan tidak valid. Harap pilih 1, 2, 3, 4, atau 5.\e[0m"
      ;;
  esac

  echo ""
done
