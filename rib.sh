#!/bin/bash

function potong_file() {
  read -p $'\e[1;34mMasukkan nama file: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan jumlah potongan per file: \e[0m' line_per_file
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

  echo -e "\e[1;32mProses pemotongan dan pembuatan file selesai.\e[0m"
}

function hapus_baris_duplikat() {
  read -p $'\e[1;34mMasukkan nama file yang ingin dihapus duplikatnya: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  sort "$input_file" | uniq > "$input_file.tmp"
  mv "$input_file.tmp" "$input_file"

  echo -e "\e[1;32mBaris duplikat dalam file $input_file telah dihapus.\e[0m"
}

function filter_domain() {
  read -p $'\e[1;34mMasukkan nama file: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan domain yang ingin difilter (contoh: .com): \e[0m' domain_suffix
  if [ -z "$domain_suffix" ]; then
    echo -e "\e[1;31mAkhiran domain tidak boleh kosong.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan nama file baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  grep "$domain_suffix" "$input_file" > "$output_file"

  echo -e "\e[1;32mProses filter domain dan pembuatan file selesai.\e[0m"
}

function show_menu() {
  echo -e "\e[1;35m===== Mr.7Mind MENU =====\e[0m"
  echo -e "\e[1;36m1. Memotong File\e[0m"
  echo -e "\e[1;36m2. Menghapus Duplikat\e[0m"
  echo -e "\e[1;36m3. Filter Sesuai Domain\e[0m"
  echo -e "\e[1;36m4. Keluar\e[0m"
  echo -e "\e[1;35m======== SIMPLE ========\e[0m"
}

while true; do
  show_menu
  read -p $'\e[1;34mPilih menu [1/2/3/4]: \e[0m' menu_choice

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
      echo -e "\e[1;33mTerima kasih, Follow https://github.com/Mr-7Mind.\e[0m"
      exit 0
      ;;
    *)
      echo -e "\e[1;31mPilihan tidak valid. Harap pilih 1, 2, 3, atau 4.\e[0m"
      ;;
  esac

  echo ""
done
